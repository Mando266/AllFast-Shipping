<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dev</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
<div class="bg-gray-500 min-h-screen flex place-items-center justify-center">
    <form action="{{ route('devtools.index') }}" class="bg-gray-200 rounded-md p-6">
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="dev_pass">
                Password
            </label>
            <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                   name="dev_pass" id="dev_pass" type="text">
        </div>
        <div class="flex items-center justify-center">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                Submit
            </button>
        </div>
    </form>
</div>

</body>
</html>