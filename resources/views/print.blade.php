<!DOCTYPE html>
<html lang="ar" dir="rtl" class=" bg-white  mx-auto">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>تقرير أمني</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body dir="rtl" class=" px-5 flex flex-col bg-white">
    <img src={{ asset('image/header.png') }}>
    <div dir="rtl" class="bg-white  rounded w-full mb-auto">
        <h1 class="text-xl underline font-bold mb-4 text-center">تقرير أمني</h1>
        <div class="flex justify-between px-20 mb-2">
            <div class="mb-4 flex items-center">
                <p class="pl-2 font-bold">اليوم:</p>
                <p class=" block w-full rounded-md">{{ \Carbon\Carbon::parse($report->date)->dayName }}</p>
            </div>
            <div class="mb-4 flex items-center">
                <p class="pl-2 font-bold">التاريخ:</p>
                <p class=" w-full rounded-md">{{ $report->date }}</p>
            </div>
            <div class="mb-4 flex items-center">
                <p class="block pl-2 font-bold">الوقت:</p>
                <p class=" block w-full rounded-md">
                    {{ \Carbon\Carbon::parse($report->time)->format('g:i A') }}
                </p>
            </div>
        </div>
        <div class="mb-4">
            <p class="block mb-5 font-bold">شرح الحالة:</p>
            <p class=" block w-full rounded-md">
                {!! Str::markdown($report->state_description) !!}
            </p>
        </div>
        <div class="mb-2">
            <p>معد التقرير:</p>
        </div>
        <div class="flex">
            <span class="pl-2">الاسم:</span>
            <p class=" w-full rounded-md">{{ $report->reporter->name }}</p>
        </div>
    </div>
  {{--   <img class="fixed-bottom justify-end mt-20" src={{ asset('image/footer.png') }}> --}}
</body>

</html>
