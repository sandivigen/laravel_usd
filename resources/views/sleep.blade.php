@extends('layouts.app')

@section('content')

<div class="panel-heading">График сна</div>

<div class="panel-body" style="padding-left: 20px">

    <?php

    $day = 24 * 60;
    $half_day = 12 * 60;
    $correction_hours = 0;
    $chart_correction = $correction_hours * 60;


    ?>
    @if($sleeps)
        @foreach($sleeps as $key => $sleep)
            <div class="row blog-posts small post-item">
                <?php

                // Вводные: ночь понедельника является первым днем
                // вс лег в 23 встал в пн в обед
                // пн лег в 23, встал в 4 ночи
                // вт лег за полночь в 1 ночи и проснулся в 15 в ср
                // ср лег за полночь в 1 ночи и проснулся в 5 утра

                // Настроейка шторма
                // ктрл + g = перейти к строке,
                // настроить колоры для стиля папок
                // эдитор - лайв темплейт: можно своиконструкции добавлять
                // эдитор - код стаил: можно стиль ифоф элсов и тд указывать
                // шифт энтер - переход на новую строку
                // контрл + пробел : выдает список доступних функций, но не смог понять кк на маке
                // ктрл + W = выделить фрагмент
                // альте + ап = выделяет область
                // альт + ентер = при оштбках
                // шифт + cmd + n = создать заметку
                // cntl + T = замена в неск местах
                // стр альт L = проставляет отступы и др(перестилизует документ)
                // трл шифт цифра = запомнить место
                // ктр q - инфа по объекту



                // Получение даты сна
                $dt_sleep_data = new DateTime($sleep->bedtime);
                $sleep_data_day[] = (int)$dt_sleep_data->format('d');                                         // Сумма в минутах, с начала суток до отхода ко сну
                //                print_r($sleep_data_day);

                // Задача: необходимо понять сон укладывается в оди сутки (с 00 до 24)
                //         или он разбит на две части
                //




                // Преведение времяни отхода ко сну к объекту time
                $dt_sleep_start = new DateTime($sleep->in_bed);
                $display_time_start_sleep[$key] = $dt_sleep_start->format('H:i'); // Вывод текста времени отхода ко сну
                $sleep_start_point = (int)$dt_sleep_start->format('i') + (int)$dt_sleep_start->format('H') * 60;                                         // Сумма в минутах, с начала суток до отхода ко сну

                // Преведение времяни пробуждения к объекту time
                $dt_sleep_end = new DateTime($sleep->awoke);
                $display_time_end_sleep[] = $dt_sleep_end->format('H:i'); // Отображение времени пробуждения
                $sleep_end_point = (int)$dt_sleep_end->format('i') + (int)$dt_sleep_end->format('H') * 60; // Точка пробуждения, мин
                $sleep_end_point_correction = $sleep_end_point + $chart_correction;





                // Проблема:
                // теперь не нужно переносить на след сутки, потому, что
                // сутки нкончаются не в 00:00 а в 12:00 (с тем условием что я на 12 часов сдвинул)
                // необходимо отслеживать сдвиг и уже от него расчитывать в каком промежутке делать разрез шейпа


                // НУжно, чтобы время 1440 от него отнималось КОРЕЦИЯ и уже если оно привешает то переностить

                    // Если точка начала сна больше, чем точка конца сна,
                    // значит день разделен на две чести

                echo '<h4>Итерация #'. ($key+1) .' ___ с '.$display_time_start_sleep[$key].' по '.$display_time_end_sleep[$key].'</h4>';
                echo '$sleep_start_point = '.$sleep_start_point.' ';
                echo '<br>';
                echo '$sleep_end_point_correction = '.$sleep_end_point_correction.' ';
                echo '<br>';




                // если скорректированная точка начала и конца больше суток то делим, если менее то влазиет
                // короче если длина дня + длина сна больше суток то делим
                // длина до начала сна = начало сна - скорректированное время

                $part_day = $sleep_start_point - $chart_correction;


                // Если лег до полуночи, то нужно склеить из кусков
                // (это можно понять, только если будут даты, иначе всегда получается до полуночи)
                // Или если точка начала сна позже конца сна


                if ($sleep_start_point > $sleep_end_point_correction) {

                    $first_part_sleep = $day - $sleep_start_point;
                    $second_part_sleep = $sleep_end_point;
                } else { // Иначе просто берем от 00:00 до конца сна и вычетаем с 00:00 до начала сна кусочек

                }
                $part_sleep = $sleep_end_point_correction - $sleep_start_point;


                if ($chart_correction > 0) {
                    echo 'делал смещение времени, ';
                    if ($sleep_start_point > $sleep_end_point) { // если начальная точка выше конечной, значит это след-й день и надо делить шейп

                        echo 'лег до полуночи, ';
                        // начальная точка отхода ко сну, скорректированная со смещением графика
                        $start_sleep_position_y[$key] = $sleep_start_point - $chart_correction;

                        $first_part_sleep = $day - $sleep_start_point;
                        $second_part_sleep = $sleep_end_point;

                        if ($part_day + $first_part_sleep +  $second_part_sleep > $day) {
                            echo 'делал разделение шейпа, ';
                            $sleep_shape_height[] = $first_part_sleep + $chart_correction;
                            $sleep_second_shape_height[] = $sleep_end_point_correction - $day;
                        }
                        else {

                            echo 'лег после полуночи, ';
                            // начальная точка отхода ко сну, скорректированная со смещением графика
                            $start_sleep_position_y[$key] = $sleep_start_point + $chart_correction;

                            echo 'не делал разделение шейпа, ';
                            $sleep_shape_height[] = $first_part_sleep + $second_part_sleep;
                        }
                    }
                    else {
                        // ЭТОТ ИФ ДУБЛИРУЕТСЯ ВЫШЕ
                        if ($sleep_start_point > $sleep_end_point) { // если начальная точка выше конечной, значит это след-й день и надо делить шейп
                            echo 'лег до полуночи, ';
                            // начальная точка отхода ко сну, скорректированная со смещением графика
                            $start_sleep_position_y[$key] = $sleep_start_point - $chart_correction;
                        } else {
                            echo 'лег после полуночи, ';
                            // начальная точка отхода ко сну, скорректированная со смещением графика
                            $start_sleep_position_y[$key] = $sleep_start_point + $chart_correction;
                        }
                        $sleep_shape_height[] = $sleep_end_point_correction - $sleep_start_point - $chart_correction;
                    }
                }



                if ($chart_correction == 0) {
//                    echo 'не делал смещение времени, ';


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


                    /**
                     * Если лег и проснулся в этот же день
                     * 1.а Не делящиеся (когда промежуток от 00:00 до 24:00)
                     *
                     */

                    // начальная точка отхода ко сну
                    $start_sleep_position_y[] = $sleep_start_point;

                    if ($sleep_start_point < $sleep_end_point AND $sleep_start_point + $sleep_end_point > $day) {
                        echo '<ul style="margin-bottom: 0;"><li>лег и проснулся до полуночи, </li></ul>';

                        // длина шейпа       =  сутки, м -  сумма начало сна, м - сумма конца сна, м
                        $sleep_shape_height[] = $sleep_end_point  - $sleep_start_point;
                        echo '$sleep_shape_height = '.$sleep_shape_height[$key].' ';

                        $sleep_case[] = 1;
                    }

                    if ($sleep_start_point > $sleep_end_point ) {
                        echo '<ul style="margin-bottom: 0;"><li>лег до полуночи, а проснулся после, </li></ul>';

                        // Первая половина шейпа этого дня, от начала сна и до конца суток
                        $sleep_shape_height[] = $day - $sleep_start_point; //  __переименовать на первый

                        // Вторая половина шейпа,
                        $sleep_second_shape_height[$key] = ($sleep_end_point + $chart_correction) / 3;

                        // Текст для второй половины
//                        $sleep_second_shape[$key] =

                        $sleep_case[] = 2;

                    }

                    if ($sleep_start_point < $sleep_end_point AND $sleep_start_point + $sleep_end_point < $day) {
                        echo '<ul style="margin-bottom: 0;"><li>лег после полуночи, </li></ul>';

                        // Нет шейпа для этого дня
                        $sleep_shape_height[] = 0;

                        // Вторая половина шейпа,
                        $sleep_second_shape_height[] = ($sleep_end_point - $sleep_start_point) / 3;

                        echo '$sleep_second_shape_height case3 = '.$sleep_second_shape_height[$key].' ';

                        $sleep_case[] = 3;

                    }



                }




                ?>
            </div>
        @endforeach
    @else
        <p>Данные не найденны</p>
    @endif

        <script>$( document ).ready(function() {
                var contaner = $('.second-part-sleep').parent().next();
                var element = $('.second-part-sleep').detach();
                $(contaner).append(element);
            });
        </script>


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

    ?>

    <svg width="auto" height="1000" style="padding: 240px 0">

        <g transform="translate(40,20)">

            <g class="birthyears" transform="">
                <?php

                $padding_start = 25;
                $padding_step = 60;
                $second_part_shape = false;
                $double_shape_sleep = false;

                for ($i=0; $i < 10; $i++) {

                    $i-1 != -1 ? $before_key = $i-1 : $before_key = 0;
                    $padding_text_end = $start_sleep_position_y[$i] /3  + 13;
                    $padding_text_start = $start_sleep_position_y[$i] / 3 + $sleep_shape_height[$i] / 3 - 7 ;
                    $start_sleep_position_y[$i] = $start_sleep_position_y[$i] / 3;
                    $sleep_shape_height[$i] = $sleep_shape_height[$i] / 3;




                    if($chart_correction == 0) {

                        echo '<g id="day-block-'.$i.'" class="birthyear" transform="translate('.$padding_start.',0)">'; // группировка
                        echo   '<rect x="-23" width="46" y="0" height="480"></rect>'; // vertical background line

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

    //                    echo   '<text y="600" x="'.($i*60).'"fill="#000">'.$i.' case = '.$sleep_case[$i].'</text>';


                        if($sleep_case[$i] == 1) {
                            echo '<rect style="fill:rgb(0,0,255);opacity:0.5" x="-23" width="46" y="'.$start_sleep_position_y[$i].'" height="'.$sleep_shape_height[$i].'"></rect>';
                            echo '<text y="'.$padding_text_end.'">'.$display_time_start_sleep[$i].'</text>';
                            echo '<text y="'.$padding_text_start.'">'.$display_time_end_sleep[$i].'</text>';

                        }

                        if($sleep_case[$i] == 2) {

                            $position_second_part_shape_text = $sleep_second_shape_height[$i] - 7;

                            echo '<rect style="fill:rgb(0,0,255);opacity:0.5" x="-23" width="46" y="'.$start_sleep_position_y[$i].'" height="'.$sleep_shape_height[$i].'"></rect>';
                            echo '<rect class="second-part-sleep" x="-23" width="46" y="0" height="'.$sleep_second_shape_height[$i].'"></rect>';

                            echo '<text y="'.$padding_text_end.'">'.$display_time_start_sleep[$i].'</text>';
                            echo '<text class="second-part-sleep" y="'.$position_second_part_shape_text.'">'.$display_time_end_sleep[$i].'</text>';

                        }

                        if($sleep_case[$i] == 3) {

                echo '<rect  x="-23" width="46" y="0" height="'.$sleep_second_shape_height[$i].'"></rect>';


                }



                        // если есть развоение шейпа без коррекции графика, то необходимо делать отступ перед началом след-го дня, при коррекции такой необходимости нет, ведь когда переноситься всегда будет прилепать к началу графика
                        ($double_shape_sleep == true) ? $position_second_part_shape = $start_sleep_position_y[$before_key] : $position_second_part_shape = 0;

                        // Если время конца сна пред-го дня больше начала текущего, значит здесь будет две полосы
                        // _Или время сна более суток
                        if($display_time_end_sleep[$before_key] > $display_time_start_sleep[$i]) {
                            $double_shape_sleep = true;

                        } else {

                            $double_shape_sleep = false;
    //                        echo   '<text y="'.$padding_text_end.'">а '.$display_time_start_sleep[$i].'</text>';
                        }

                        // если в пред-й этерации оказался кусок шейпа который не влез
    //                    if($second_part_shape == true) {
                        if($second_part_shape == 123) {

                            // берем кусок шейпа из перд-й итерации
                            $position_second_part_shape_text = $sleep_second_shape_height[$before_key] / 3 - 7;
                            $sleep_second_shape_height[$before_key] = $sleep_second_shape_height[$before_key] / 3;

                            // если есть развоение шейпа без коррекции графика, то необходимо вычесть разницу отступа перед шейпом, ведь теперь он выпирает на сдвиг который мы добавили перед ним
                            ($double_shape_sleep == true) ? $sleep_second_shape_height[$before_key] = $sleep_second_shape_height[$before_key] - $position_second_part_shape : '';


    //                        echo   '<text y="'.$position_second_part_shape_text.'">й'.$display_time_end_sleep[$before_key].'</text>';

                        }

                        if(isset($sleep_second_shape_height[$i])) {
                            // для переброски кучка шейпа на следующий день(не смог настройить в той же
                            // шейп попадает под блок след-го дня
                            $second_part_shape = true; // если есть кусок шейпа, то в след-й итерации будем его вставлять
                        } else {
                            $second_part_shape = false; // если куска нет, то выключим обращение к нему в след-й итерации
                        }

                    }






















                if($chart_correction > 0) {

                    echo '<g class="birthyear" transform="translate('.$padding_start.',0)">';
                        echo   '<rect x="-23" width="46" y="0" height="480"></rect>'; // vertical background line
                        echo     '<rect style="fill:rgb(0,0,255);opacity:0.5" x="-23" width="46" y="'.$start_sleep_position_y[$i].'" height="'.$sleep_shape_height[$i].'"></rect>';


    //                echo   '<text y="-50" fill="#000">height</text>';
                    echo   '<text y="-68" fill="#000">'.$key_minus.'</text>';
    //
    //                echo   '<text y="-80" fill="#000">height 2</text>';
    //                echo   '<text y="-68" fill="#000">'.$sleep_second_shape_height[$i].'</text>';

                    // если есть развоение шейпа без коррекции графика, то необходимо делать отступ перед началом след-го дня, при коррекции такой необходимости нет, ведь когда переноситься всегда будет прилепать к началу графика
                    ($double_shape_sleep == true) ? $position_second_part_shape = $start_sleep_position_y[$key_minus] : $position_second_part_shape = 0;


                    if($double_shape_sleep == true) {
                            $key = $i-1; // доступ к ключу предыдущий итерации
                            echo   '<text y="'.$padding_text_start.'">x'.$display_time_start_sleep[$key].'</text>';
                            echo   '<text y="650" fill="#000">true</text>';

                        }

                        if ($chart_correction > 0) { // если была коррекция и лег за полнось то оставляем вывод времени тут же, если не было необходимо перенести его на след-ю колонку
                            echo   '<text y="'.$padding_text_end.'">'.$display_time_start_sleep[$i].'</text>';
                        }

                        else {

                            $before_key = $i-1;
                            (isset($display_time_end_sleep[$before_key]) ?  '' : $display_time_end_sleep[$before_key] = 0);


                            echo   '<text y="610" fill="#000">'.$display_time_end_sleep[$before_key].'</text>';
                            echo   '<text y="630" fill="#000">'.$display_time_start_sleep[$i].'</text>';


                            if($display_time_end_sleep[$before_key] > $display_time_start_sleep[$i]) {
                                $double_shape_sleep = true;
                                echo   '<text y="590" fill="#000">double</text>';

                            } else {
                                $double_shape_sleep = false;
                                echo   '<text y="'.$padding_text_end.'">'.$display_time_start_sleep[$i].'</text>';
                            }
                        }




                        // если в пред-й этерации оказался кусок шейпа который не влез
                        if($second_part_shape == true) {

                            $key = $i-1; // доступ к ключу предыдущий итерации

                            // берем кусок шейпа из перд-й итерации
                            $position_second_part_shape_text = $sleep_second_shape_height[$key] / 3 - 7;
                            $sleep_second_shape_height[$key] = $sleep_second_shape_height[$key] / 3;

                    // если есть развоение шейпа без коррекции графика, то необходимо вычесть разницу отступа перед шейпом, ведь теперь он выпирает на сдвиг который мы добавили перед ним
                    ($double_shape_sleep == true) ? $sleep_second_shape_height[$key] = $sleep_second_shape_height[$key] - $position_second_part_shape : '';


                            echo '<rect x="-23" width="46" y="'.$position_second_part_shape.'" height="'.$sleep_second_shape_height[$key].'"></rect>';
                            echo   '<text y="'.$position_second_part_shape_text.'">'.$display_time_end_sleep[$key].'</text>';


                            echo   '<text y="550" fill="#000">second</text>';
                            echo   '<text y="562" fill="#000">part</text>';
                        }

                        if(isset($sleep_second_shape_height[$i])) {
                            // для переброски кучка шейпа на следующий день(не смог настройить в той же
                            // шейп попадает под блок след-го дня
                            $second_part_shape = true; // если есть кусок шейпа, то в след-й итерации будем его вставлять
                        } else {
                            echo   '<text y="'.$padding_text_start.'">'.$display_time_end_sleep[$i].'</text>';
                            $second_part_shape = false; // если куска нет, то выключим обращение к нему в след-й итерации
                        }
                    }

                    echo '</g>';


                    // Увеличиваем велечину позиции начала следующего отступа
                    $padding_start = $padding_start + $padding_step;
                }
                ?>
            </g>


            <g class="y axis" transform="translate(900,0)">


                <?php

                /**
                 *
                 * Отрисовка линий часов
                 *
                 */

                $step_start = 0;
                $step = 20;
//                $correction_hours = 0;

                for ($i=0; $i <= 24; $i++) {

                    if ($i == 24) {
                        echo '<g class="tick zero" transform="translate(0,'.$step_start.')" style="opacity: 1;">';
                    } else {
                        echo '<g class="tick" transform="translate(0,'.$step_start.')" style="opacity: 1;">';
                    }

                    echo '<text dy=".32em" x="-930" y="0" style="text-anchor: start;">'.$correction_hours.':00</text>';
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
             * Отрисовка дней недели
             *
             */

                $week_days = ['пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс',];
                $start_step = 25;
                $step = 60;

                for($i=0; $i <= 9; $i++) {

                    $before_i = $i - 1;
                    if($before_i < 0) $before_i = 6;

                    echo '<text class="age" x="'.$start_step.'"  y="-24" dy=".71em">'.$week_days[$before_i].'</text>'; // дни недели верх
                    echo '<text class="age" x="'.$start_step.'" y="-12" dy=".71em">'.$sleep_data_day[$before_i].'</text>'; // числа верх

                    echo '<text class="age" x="'.$start_step.'"  y="485" dy=".71em">'.$week_days[$i].'</text>'; // дни недели
                    echo '<text class="age" x="'.$start_step.'" y="500" dy=".71em">'.$sleep_data_day[$i].'</text>'; // числа


                    $start_step = $start_step + $step;

                }

//                foreach ($week_days as $key => $value) {
//                    echo '<text class="age" x="'.$start_step.'"  y="485" dy=".71em">'.$value.'</text>';
//                    echo '<text class="age" x="'.$start_step.'" y="500" dy=".71em">'.$sleep_data_day[$key].'</text>';
//
//                    echo '<text class="age" x="'.$start_step.'"  y="-24" dy=".71em">'.$value.'</text>';
//                    echo '<text class="age" x="'.$start_step.'" y="-12" dy=".71em">'.$sleep_data_day[$key].'</text>';
//
////                    $day_start++;
//                    $start_step = $start_step + $step;
//                }

            ?>

        </g>
    </svg>
</div>




@endsection

