<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="bg-gray-500 min-h-screen">
    <div class="bg-gray-200 rounded-md p-6">
        <form action="{{ route('devtools.sql.select-post') }}" method="post">
            @csrf
            <input name="dev_pass" value="{{ $devPass }}" hidden>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="sql">
                        SQL Select Statement
                    </label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-black rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                           name="sql" id="sql">
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ml-3 mt-3 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>