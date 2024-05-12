@vite(['resources/css/app.css'])

<x-filament-panels::page>

    <div class="printDiv">
        <x-filament::button size="md" class="print">
            طباعه
        </x-filament::button>
    </div>

    <div class="hidden invoice">
        <div class="bill">
            <div class="brand">
                {{-- <strong> Harrison Glenn </strong> --}}
                <strong> {{$record->company->name}} </strong>
            </div>
            <div class="address">
                {{$record->company->address}}
                <br> {{$record->company->phone}}
            </div>
            <div class="shop-details border-dashed border-2 border-black">
                فاتوره توريد
            </div>
            <div class="bill-details">
                <div class="flex justify-between">
                    <div>رقم الفاتوره : {{$record->id}}</div>
                    <div>رقم امر التوريد : {{$record->supply_order->supply_number}}</div>
                </div>
                <div class="flex justify-between">
                    <div>BILL DATE: {{$record->created_at}}</div>
                    <div>TIME: {{$record->created_at}}</div>
                </div>
            </div>
            <table class="table">
                <tbody class="header text-right">
                    <tr>
                        <td>اسم العميل</td>
                        <td>-</td>
                        <td class="text-left">{{$record->customers->name}}</td>
                    </tr>
                    <tr>
                        <td>رقم امر التوريد</td>
                        <td>-</td>

                        <td class="text-left">{{$record->supply_order->supply_number}}</td>
                    </tr>
                    <tr>
                        <td>الموبيل</td>
                        <td>-</td>

                        <td class="text-left">{{$record->customers->phone}}</td>
                    </tr>
                    <tr>
                        <td>الفرع</td>
                        <td>-</td>

                        <td class="text-left">{{$record->customers->address[$record->branch] ?? 'لم يعد هذا القرع موجود'}}</td>
                    </tr>
                </tbody>
                <tbody class="header text-right">
                    <tr>
                        <td>مقاول الشحن</td>
                        <td>-</td>

                        <td class="text-left">{{$record->transportation_companies->name}}</td>
                    </tr>
                    <tr>
                        <td>اسم السائق</td>
                        <td>-</td>
                        <td class="text-left">{{$record->transport_workers->name}}</td>
                    </tr>
                    <tr>
                        <td>رقم العربيه</td>
                        <td>-</td>

                        <td class="text-left">{{$record->transport_workers->car_number}}</td>
                    </tr>
                    <tr>
                        <td>نوع الحموله</td>
                        <td>-</td>
                        <td class="text-left">{{$record->materials->name}}</td>
                    </tr>
                    <tr>
                        <td>المحجر</td>
                        <td>-</td>

                        <td class="text-left">{{$record->quarrie->name}}</td>
                    </tr>
                    <tr >
                        <td>العهده</td>
                        <td>-</td>

                        <td class="text-left">{{$record->opening_amount}}</td>
                    </tr>
                </tbody>
            </table>
            <br> رقم البوليصه: {{$record->reference}}
            <br> موظف : احمد ابراهيم
        </div>
    </div>


    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif

    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers :active-manager="$this->activeRelationManager" :managers="$relationManagers" :owner-record="$record"
            :page-class="static::class" />
    @endif

    <script>
        document.querySelector(".print").addEventListener('click', function() {
            window.print();
        });
    </script>
</x-filament-panels::page>
