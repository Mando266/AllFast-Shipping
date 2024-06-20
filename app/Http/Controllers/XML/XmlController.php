<?php

namespace App\Http\Controllers\XML;

use App\Filters\ManifestXml\ManifestXmlIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Voyages\VoyagePorts;
use App\Models\Voyages\Voyages;
use App\Models\Xml\Xml;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class XmlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize(__FUNCTION__, Xml::class);

        $xmls = Xml::filter(new ManifestXmlIndexFilter(request()))
            ->orderBy('id', 'desc')
            ->where('company_id', Auth::user()->company_id)
            ->with('bldraft', 'voyage.bldrafts', 'port')
            ->paginate(30);

        $blDraftNo = BlDraft::where('company_id', Auth::user()->company_id)->get();
        $voyages = Voyages::with('vessel')
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return view('bldraft.manifestXml.index', [
            'items' => $xmls,
            'blDraftNo' => $blDraftNo,
            'voyages' => $voyages,
        ]);
    }

    public function selectManifest()
    {
        $voyages = Voyages::where('company_id', Auth::user()->company_id)
            ->with('voyagePorts.port')
            ->get();

        return view('bldraft.manifestXml.selectManifest', [
            'voyages' => $voyages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $voyage = Voyages::where('id', request()->voyage_id)
            ->with('bldrafts')
            ->first();

        $bldraft = $voyage->bldrafts->first();

        if ($voyage->bldrafts->count() == 0) {
            return back()->with('error', 'There is no BL on this voyage');
        }

        return redirect()->route('bldraft.serviceManifest', [
            'bldraft' => $bldraft->id,
            'voyage' => $voyage,
            'loadPort' => request()->load_port_id ?? null,
            'dischargePort' => request()->discharge_port_id ?? null,
            'xml' => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->loadPort != null) {
            $port = $request->loadPort;
            $is_load_port = 1;
        } else {
            $port = $request->dischargePort;
            $is_load_port = 0;
        }

        $voyageId = $request->voyage_id;

        $bldrafts = BlDraft::whereHas('booking', function ($query) use ($voyageId) {
            $query->where('voyage_id', $voyageId)
                ->orWhere('voyage_id_second', $voyageId);
        })
            ->with('booking', 'blDetails.container')
            ->get();

        $voyage = Voyages::where('id', $voyageId)->first();
        $voyage->bldrafts = $bldrafts;

        foreach ($voyage->bldrafts as $bldraft) {
            foreach ($bldraft->blDetails as $item) {
                if ($item->container == null) {
                    return back()->with('error', 'There is an unselected container in Bill Of Lading No: ' . $bldraft->ref_no);
                }
            }
        }

        $xmlContent = $this->createXml($request->voyage_id, $port); // Generate the XML content

        $setting = Setting::first();
        Xml::create([
            'company_id' => Auth::user()->company_id,
            'ref_no' => $setting->xml_ref_no,
            'is_load_port' => $is_load_port,
            'voyage_id' => $request->voyage_id,
            'port_id' => $port,
        ]);
        $setting->xml_ref_no = $setting->xml_ref_no + 1;
        $setting->save();

        // Set appropriate headers for download
        $headers = [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="manifest.xml"',
        ];

        // Return the XML content as a download response
        $response = response($xmlContent, 200, $headers);

        // Redirect back to the xml.index route after downloading
        return $response->header('Refresh', '5;url=' . route('xml.index'))
            ->header('Success-Message', trans('Manifest XML.Created'));
    }

    private function createXml($voyage_id, $port)
    {
        $voyageId = $voyage_id;

        $bldrafts = BlDraft::whereHas('booking', function ($query) use ($voyageId) {
            $query->where('voyage_id', $voyageId)
                ->orWhere('voyage_id_second', $voyageId);
        })
            ->with('booking', 'blDetails.container.containersTypes', 'booking.quotation', 'voyage.vessel',
                'customer', 'customerNotify', 'customerConsignee', 'loadPort.country', 'dischargePort.country')
            ->get();

        $voyage = Voyages::where('id', $voyageId)
            ->with('vessel', 'line.country')
            ->first();
        $voyage->bldrafts = $bldrafts;
        $bldraft = $voyage->bldrafts->first();

        // Create a new XML document
        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');
        $xmlDoc->formatOutput = true;

        // Create the root element <ManifestData>
        $manifestData = $xmlDoc->createElement('ManifestData');
        $xmlDoc->appendChild($manifestData);

        // Getting arrival and departure dates
        $etaDate = VoyagePorts::where('voyage_id', $voyage->id)
            ->where('port_from_name', $port)
            ->pluck('eta')
            ->first();
        $etdDate = VoyagePorts::where('voyage_id', $voyage->id)
            ->where('port_from_name', optional($bldraft->loadPort)->id)
            ->pluck('etd')
            ->first();

        // Create the <GeneralInfo> element and add child elements
        $generalInfo = $xmlDoc->createElement('GeneralInfo');
        $this->addItemToElement($xmlDoc, $generalInfo, $etaDate, 'MNFSTArrivalDate');
        $this->addItemToElement($xmlDoc, $generalInfo, $voyage->voyage_no, 'MNFSTVoyageNumber');
        $this->addItemToElement($xmlDoc, $generalInfo, 0, 'MNFSTPassengerIndicator');
        $this->addItemToElement($xmlDoc, $generalInfo, 22, 'MNFSTWarehouse');
        $this->addItemToElement($xmlDoc, $generalInfo, $voyage->vessel->call_sign, 'MNFSTCarrierCallSign');
        $this->addItemToElement($xmlDoc, $generalInfo, $voyage->line->name, 'MNFSTCarrierName');
        $this->addItemToElement($xmlDoc, $generalInfo, optional(optional($voyage->line)->country)->name, 'MNFSTCarrierCountry');
        $this->addItemToElement($xmlDoc, $generalInfo, $voyage->bldrafts->count(), 'MNFSTBillsOfLadingCount');
        $manifestData->appendChild($generalInfo);

        // Create the <CargoData> element
        $cargoData = $xmlDoc->createElement('CargoData');

        foreach ($voyage->bldrafts as $bldraft) {
            $BillOfLading = $xmlDoc->createElement('BillOfLading');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->ref_no, 'BOLNumber');
            $this->addItemToElement($xmlDoc, $BillOfLading, $etdDate, 'BOLLoadingDate');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->is_transhipment, 'BOLTransitIndicator');
            $this->addItemToElement($xmlDoc, $BillOfLading, 2, 'BOLConsolidation');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->customerConsignee->name, 'BOLConsigneeName');
            $this->addItemToElement($xmlDoc, $BillOfLading, optional($bldraft->customerConsignee)->tax_card_no, 'ImporterTaxNumber');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->customer_consignee_details, 'BOLConsigneeAddress');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->customerNotify->name, 'BOLNotifyPartyName');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->customer_notifiy_details, 'BOLNotifyPartyAddress');
            $this->addItemToElement($xmlDoc, $BillOfLading, substr($bldraft->loadPort->code, 2), 'BOLLoadingPort');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->loadPort->country->prefix, 'BOLLoadingCountry');
            $this->addItemToElement($xmlDoc, $BillOfLading, substr($bldraft->dischargePort->code, 2), 'BOLUnLoadingPort');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->dischargePort->country->prefix, 'BOLUnLoadingCountry');
            $this->addItemToElement($xmlDoc, $BillOfLading, 560161093, 'BOLShippingAgent');
            $this->addItemToElement($xmlDoc, $BillOfLading, 22, 'BOLWarehouse');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->blDetails->count(), 'BOLItemsCount');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->descripions, 'BOLCargoDesc');
            $this->addItemToElement($xmlDoc, $BillOfLading, $bldraft->booking->acid, 'ACID');
            $this->addItemToElement($xmlDoc, $BillOfLading, optional($bldraft->booking)->exportal_id, 'ExporterNumber');

            foreach ($bldraft->blDetails as $item) {
                if ($item->container == null) {
                    return back()->with('error', 'There is an unselected container in Bill Of Lading No: ' . $bldraft->ref_no);
                }
                $Item = $xmlDoc->createElement('Item');
                $this->addItemToElement($xmlDoc, $Item, $bldraft->movement == 'FCL/FCL' ? 2 : 1, 'ItemShipmentType');
                $this->addItemToElement($xmlDoc, $Item, $item->container->code, 'ItemContainerNO');
                
                switch ($item->container->containersTypes->code) {
                    case 'DV':
                        $containerType = 1;
                        break;
                    case 'HC':
                    case 'FR':
                        $containerType = 2;
                        break;
                    case 'RF':
                        $containerType = 3;
                        break;
                    case 'TK':
                        $containerType = 4;
                        break;
                    default:
                        $containerType = 1;
                        break;
                }
                $this->addItemToElement($xmlDoc, $Item, $containerType, 'ItemContainerType');
                $this->addItemToElement($xmlDoc, $Item, substr($item->container->containersTypes->name, 0, 2), 'ItemContainerVolume');
                $this->addItemToElement($xmlDoc, $Item, $item->seal_no, 'ItemShipingSeal');
                $this->addItemToElement($xmlDoc, $Item, $item->description, 'ItemCargoDesc');
                $this->addItemToElement($xmlDoc, $Item, $item->packs, 'ItemExpQuantity');
                $this->addItemToElement($xmlDoc, $Item, 'CNTS', 'ItemExpQTYUOM');
                $this->addItemToElement($xmlDoc, $Item, optional($item)->gross_weight, 'ItemExpGrossWeight');
                $this->addItemToElement($xmlDoc, $Item, 'KGM', 'ItemExpGWUOM');
                $this->addItemToElement($xmlDoc, $Item, $item->net_weight, 'ItemContentPackagesWeight');
                $this->addItemToElement($xmlDoc, $Item, 'CNTS', 'ItemContentQTYUOM');
                $this->addItemToElement($xmlDoc, $Item, $item->packs, 'ItemContentPackagesQuantity');
                $BillOfLading->appendChild($Item);
            }
            $cargoData->appendChild($BillOfLading);
        }

        $manifestData->appendChild($cargoData);

        // Generate the XML content
        return $xmlDoc->saveXML();
    }

    private function addItemToElement(\DOMDocument $xmlDoc, \DOMElement $parentElement, $data, $key)
    {
        // Ensure data is UTF-8 encoded
        $data = utf8_encode($data);

        // Convert special characters to XML entities
        $data = htmlspecialchars($data, ENT_XML1, 'UTF-8');

        // Remove non-printable characters
        $data = preg_replace('/[^\PC\s]/u', '', $data);

        // Create the element and set its value
        $element = $xmlDoc->createElement($key, $data);

        // Append the element to the parent element
        $parentElement->appendChild($element);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $xml = Xml::where('id', $id)
            ->with('voyage.bldrafts')
            ->first();

        $voyage = $xml->voyage;
        $bldraft = $voyage->bldrafts->first();

        return redirect()->route('bldraft.serviceManifest', [
            'bldraft' => $bldraft->id,
            'voyage' => $voyage,
            'loadPort' => $xml->is_load_port ? $xml->port_id : null,
            'dischargePort' => $xml->is_load_port ? null : $xml->port_id,
            'xml' => true,
        ]);
    }
}
