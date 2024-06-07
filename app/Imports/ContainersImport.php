<?php

namespace App\Imports;

use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\ContinerOwnership;
use App\Services\ContainerNumService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Master\LessorSeller;


class ContainersImport implements ToModel, WithHeadingRow
{

    protected ContainerNumService $containerValidator;
    protected array $errors;

    public function __construct()
    {
        $this->containerValidator = new ContainerNumService();
    }

    public function model(array $row)
    {
        $containerType = $row['container_type_id'];
        $containerNumber = $row['code'];
        $seller = $row['description'];

        $user = Auth::user();
        $codeIsValid = $this->containerValidator->isValid($containerNumber);
        if (!$codeIsValid) {
            $this->errors[] = "This Container Number: {$containerNumber} Is Not correct!!!";
            return session()->flash('message', implode(PHP_EOL, $this->errors));
        }

        $row['container_type_id'] = ContainersTypes::where('name', $row['container_type_id'])->pluck('id')->first();
        $row['container_ownership_id'] = ContinerOwnership::where('name', $row['container_ownership_id'])->pluck(
            'id'
        )->first();
        $row['description'] = LessorSeller::where('name', $row['description'])->pluck('id')->first();

        if(!$row['description']){

            return session()->flash('message',"This Lessor/Seller: {$seller} Not found ");
        }

        if (!$row['container_type_id']) {
            $this->errors[] = "This Container Type: {$containerType} Not found!!!";
            return session()->flash('message', implode(PHP_EOL, $this->errors));
        }

        $containerDuplicate = Containers::where('code', $row['code'])->where('company_id', $user->company_id)->first();

        if ($containerDuplicate != null) {
            $this->errors[] = 'This Container Number: ' . $containerNumber . " Already Exists!!!";
            return session()->flash('message', implode(PHP_EOL, $this->errors));
        }

        $ImportContainers = Containers::create([
            'code' => $row['code'],
            'iso' => $row['iso'],
            'container_type_id' => $row['container_type_id'],
            'description' => $row['description'],
            'tar_weight' => $row['tar_weight'],
            'max_payload' => $row['max_payload'],
            'container_ownership_id' => $row['container_ownership_id'],
            'production_year' => $row['production_year'],
            'is_transhipment' => $row['is_transhipment'],
            'SOC_COC'=> $row['SOC_COC'],
        ]);
        $ImportContainers->company_id = $user->company_id;
        $ImportContainers->save();

        return $ImportContainers;
    }

}
