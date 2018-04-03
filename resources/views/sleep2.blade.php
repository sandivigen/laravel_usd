@extends('layouts.app')

@section('content')


    <div class="panel-heading">График сна</div>
    <div class="panel-body" style="padding-left: 20px">
        <a href="sleep2/create">add sleep</a> -
        <a href="sleep">sleep 1</a>
        <hr>





        <?php
        /* Установка русской локали */
        setlocale(LC_TIME, 'ru_RU.UTF-8');






        // Вводные: ночь понедельника является первым днем
        // вс лег в 23 встал в пн в обед
        // пн лег в 23, встал в 4 ночи
        // вт лег за полночь в 1 ночи и проснулся в 15 в ср
        // ср лег за полночь в 1 ночи и проснулся в 5 утра

        // Дэбаг данных
        //                            echo '<h4>Итерация #'. ($key+1) .' ___ с '.$text_top[$key].' по '.$text_bot[$key].'</h4>';
        //                            echo '$sleep_start_point = '.$sleep_start_point.' ';
        //                            echo '<br>';
        //                            echo '$sleep_end_point_correction = '.$sleep_end_point_correction.' ';
        //                            echo '<br>';
        ?>

        <?php

        /**
         * Отрисовка шейпов
         */

        /**
         * Кейсы
         *
         * 1.  Когда отрисовывается шейп этого дня
         * 1.а Не делящиеся (когда промежуток от 00:00 до 24:00)
         * 1.б С переносом на следующий
         *
         * 2.  Когда не отрисовывается шейп этого дня
         * 2.а Но полностью переносится на следующий
         *
         */

        $padding_start = 25;
        $padding_step = 60;

//        $start_date = new DateTime('2018-04-12');
//        echo (int)$start_date->format('d');



//            // Отформатируем массив с данными событий, чтобы найти дни без событий и
//            // создать для них пустую ячейку, чтобы показать в графике пропущенный день
            $sleeps = array(); // создадим пустой массив, в котором будем записывать результат обхода
//            for ($i = 8; $i >= 0; $i--) { // нам нужны данные за 6 последних дней
//
//                $dinamic_date_timestamp = strtotime('-'.$i.' day midnight'); // динамически выбирает дату, начиная с 6 дней назад, в метке времени


                $element_count = 0;

                foreach ($events as $key => $shape_data) { // обойдем массив на поиск совпадения этой даты в массиве(поищим эту дату в массиве)

//                    echo $dinamic_date_timestamp.' <br> '.strtotime($shape_data->start_date).'<hr>';

//                    if ($dinamic_date_timestamp == strtotime($shape_data->start_date)) { // если дата есть в какой-либо записи, то создадим item


//                        $sleeps[$key]['start_date'] = $shape_data->start_date;
                        $sleeps[$shape_data->start_date][$key]['start_time'] = $shape_data->start_time;
                        $sleeps[$shape_data->start_date][$key]['end_time']   = $shape_data->end_time;
                        $sleeps[$shape_data->start_date][$key]['event_category']   = $shape_data->event_category;

//                        $sleeps[$shape_data->start_date][$i]['start_time'] = $shape_data->start_time;
//                        $sleeps[$shape_data->start_date][$i]['end_time'] = $shape_data->end_time;

//                        break; // если мы нашли запись для этой даты, то нам необходимо прервать цикл, чтобы не перезаписать данные

//                    } else { // если нет, то создадим пустой порядковый item
//                        $sleeps[$i]['start_date'] = strftime("%Y-%m-%d", $dinamic_date_timestamp); // преобразуем метку времени динамической даты в формат
//                        $sleeps[$i]['start_time'] = ''; // пустое поле, в этот день нет мероприятия
//                        $sleeps[$i]['end_time']   = ''; // пустое поле, в этот день нет мероприятия
//                    }
                }
//            }






        // Отформатируем массив с данными событий, чтобы найти дни без событий и
        // создать для них пустую ячейку, чтобы показать в графике пропущенный день
//                $sleeps = array(); // создадим пустой массив, в котором будем записывать результат обхода
                for ($i = 6; $i >= 0; $i--) { // нам нужны данные за 6 последних дней
                    echo '<hr>';
                    echo '============='.$i.'==========<br>';

                    $dinamic_date_timestamp = strtotime('-'.$i.' day midnight'); // динамически выбирает дату, начиная с 6 дней назад, в метке времени
                    $take_data = strftime("%Y-%m-%d", $dinamic_date_timestamp); // приведем unix код к формату

//                    проверить есть ли тако2 ключь, если нет, то добавить, если естьто не чего не делать

                    $have_year = false;

                    foreach ($sleeps as $key => $shape_data) { // обойдем массив на поиск совпадения этой


//                        print_r($shape_data);
                        echo $take_data.'<br>';
//                        echo $take_data.'+'.$dinamic_date_timestamp.'<br>';
//                        echo strtotime($key).'<hr>';
//                        echo $key.'+'.strtotime($key);
                        echo $key;

                        if ($dinamic_date_timestamp == strtotime($key)) { // если дата есть в какой-либо записи, то создадим item
//                            $count_year++;
                            $have_year = true;
//                            echo ' +x+<br>';

                        }
                        else {
//                            echo ' -x-<br>';
                        }




                    }

                    if (!$have_year) {
//                        $arr[$take_data] = 'empty';
                        $arr[$take_data][$i]['start_time'] = '12:00:00';
                        $arr[$take_data][$i]['end_time'] = '14:00:00';
                        $arr[$take_data][$i]['event_category'] = '0';

                        $sleeps = array_merge($sleeps, $arr);
                    }
//                    echo '<br>c='.$count_year.'';



                }


        ksort($sleeps);



        echo '<pre><code>';
        print_r($sleeps);
        echo '</pre></code>';




        // Отформатируем массив с данными событий, чтобы найти дни без событий и
        // создать для них пустую ячейку, чтобы показать в графике пропущенный день
        //        $sleeps = array(); // создадим пустой массив, в котором будем записывать результат обхода
        //        for ($i = 5; $i >= 0; $i--) { // нам нужны данные за 6 последних дней
        //
        //            $dinamic_date_timestamp = strtotime('-'.$i.' day midnight'); // динамически выбирает дату, начиная с 6 дней назад, в метке времени
        //
        //            foreach ($events as $key => $shape_data) { // обойдем массив на поиск совпадения этой даты в массиве(поищим эту дату в массиве)
        //                echo $shape_data.'=';
        //                if ($dinamic_date_timestamp == strtotime($shape_data->start_date)) { // если дата есть в какой-либо записи, то создадим item
        //
        //                    $sleeps[$shape_data->start_date][]['start_date'] = $shape_data->start_date;
        //                    $sleeps[$shape_data->start_date][]['start_time'] = $shape_data->start_time;
        //                    $sleeps[$shape_data->start_date][]['end_time']   = $shape_data->end_time;
        //                    break; // если мы нашли запись для этой даты, то нам необходимо прервать цикл, чтобы не перезаписать данные
        //                } else { // если нет, то создадим пустой порядковый item
        //                    $sleeps[$i]['start_date'] = strftime("%Y-%m-%d", $dinamic_date_timestamp); // преобразуем метку времени динамической даты в формат
        //                    $sleeps[$i]['start_time'] = ''; // пустое поле, в этот день нет мероприятия
        //                    $sleeps[$i]['end_time']   = ''; // пустое поле, в этот день нет мероприятия
        //                }
        //            }
        //        }


        echo '<pre><code>';
//            print_r($flowers_11);
        echo '</pre></code>';

//        $sleeps = '';
        ?>




        <svg class="sleep-graph">

            <g transform="translate(40,20)">

                <g class="days-group" transform="translate(60,0)">



                    <?php
                    $padding_start = 25;
                    $padding_start = -35;
                    $padding_step = 60;

                    $day = 24 * 60;
                    $half_day = 12 * 60;
                    $correction_hours = 0;
                    $chart_correction = $correction_hours * 60;


//                    $sleeps = '';

                    ?>








                    @if($sleeps)

                            @foreach($sleeps as $year => $sleep)


                            <?php

//                            // Получение даты начала события
//                            $dt_start_date = new DateTime($sleep->start_date); // превести у объекту времени
//                            $start_date_day[] = (int)$dt_start_date->format('d'); // получить числовую дату дня

                            // Сумма в минутах, с начала суток до отхода ко сну


                            // Сделаем класс для закраски фона выходных дней
                            $weekend = date('N', strtotime($year)) >= 6;
                            $weekend ? $weekend = 'bg-weekend-1' : '';


                            echo '<g class="day-item" transform="translate(' . $padding_start . ',0)">'; // группировка всех шейпов дня
                            echo    '<rect class="day-background '.$weekend.'" x="-23" width="46" y="0" height="480"></rect>'; // разметка дня: фон и линии




                            // Позиция текста
                            //                            $text_first_top_position = $sleep_start_point / 3 + 12.5;
                            //                            $text_first_bot_position = $sleep_start_point / 3 + $sleep_shape_first_height - 7.5;


                                foreach($sleep as $k => $item) {
                                    // Преведение времяни отхода ко сну к объекту time
                                    $dt_start_time = new DateTime($item['start_time']);
                                    $start_time_point = (int)$dt_start_time->format('i') + (int)$dt_start_time->format('H') * 60;                                         // Сумма в минутах, с начала суток до отхода ко сну

                                    // Преведение времяни отхода ко сну к объекту time
                                    $dt_end_time = new DateTime($item['end_time']);
                                    $end_time_point = (int)$dt_end_time->format('i') + (int)$dt_end_time->format('H') * 60;

                                    // Первая часть шейпа
                                    $event_height = ($end_time_point - $start_time_point) / 3;
                                    $shape_start_position = $start_time_point / 3;



                                    switch ($item['event_category']) {
                                        case 0:
                                            $class_category = 'shape-empty';
                                            break;
                                        case 1:
                                            $class_category = 'shape-sleep';
                                            break;
                                        case 2:
                                            $class_category = 'shape-sleep-before';
                                            break;
                                        case 3:
                                            $class_category = 'shape-empty-after';
                                            break;
                                    }

                                    echo '<rect class="'.$class_category.'" y="' . $shape_start_position . '" x="-23" height="' . $event_height . '"></rect>';

                                }

                            //                            echo '<text y="' . $text_first_top_position . '">' . $text_top[$key] . '</text>';
                            //                            echo '<text y="' . $text_first_bot_position . '">' . $text_bot[$key] . '</text>';




                            // вывод дат
                            if ($chart_correction == 0) {
                                $week_day_prev_unix = strtotime('-1 day', strtotime($year)); // get unix time(получим большое число)
                                $week_day_prev = strftime("%a", $week_day_prev_unix); // получить название дня недели в сокр.виде, пред.дня
                                $num_day_prev_unix = strtotime('-1 day', strtotime($year));
                                $num_day_prev = strftime("%d", $num_day_prev_unix); // получить числя дня недели, пред.дня
                                $week_day = strftime("%a", strtotime($year));// получить название дня недели в сокр.виде, сего.дня
                                $num_day = strftime("%d", strtotime($year)); // получить числя дня недели, сего.дня
                            }
                            echo '<text class="week-day" y="-30" dy=".71em">'.$week_day_prev.'</text>'; // дни недели верх
                            echo '<text class="week-day" y="-18" dy=".71em">'.$num_day_prev.'</text>'; // числа верх
                            echo '<text class="week-day" y="490" dy=".71em">'.$week_day.'</text>'; // дни недели
                            echo '<text class="week-day" y="502" dy=".71em">'.$num_day.'</text>'; // числа

                            echo '</g>';

                            $padding_start = $padding_start + $padding_step;

                            ?>
                        {{--@endfor--}}
                        @endforeach
                    @else
                        <p>Данные не найденны</p>
                    @endif

                    <?php
                    // вывести пустой пространство дня для завтра
                    if ($chart_correction == 0) {
//                        echo '<g class="birthyear" transform="translate(' . $padding_start . ',0)">'; // группировка
//                        echo    '<rect x="-23" width="46" y="0" height="480" ></rect>'; // vertical background line
//                        echo '</g>';
                    }
                    ?>

                </g>

                {{-- Отрисовка линий часов --}}
                <g class="marking-hours" transform="translate(900,0)">

                    <?php

                    $step_start = 0;
                    $step = 20;
                    //                $correction_hours = 0;

                    for ($i=0; $i <= 24; $i++) {

                        if ($i == 24) {
                            echo '<g class="hour-item zero" transform="translate(0,'.$step_start.')" >';
                        } else {
                            echo '<g class="hour-item" transform="translate(0,'.$step_start.')" >';
                        }

                        echo '<text x="-930" y="3">'.$correction_hours.':00</text>';
                        echo '<line x2="-900" y2="0"></line>';
                        echo '</g>';
                        $step_start = $step_start + $step;
                        $correction_hours = $correction_hours + 1;
                        if ($correction_hours == 24) $correction_hours = 0;
                    }

                    ?>

                </g>

                <?php

                /**
                 *
                 * Отрисовка дней недели и чисел
                 *
                 */



                ?>

            </g>
        </svg>
    </div>




@endsection

