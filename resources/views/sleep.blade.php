@extends('layouts.app')

@section('content')

<script>$( document ).ready(function() {
        $('.second-part-sleep3').each(function() {
//            $(this).appendTo($(this).parent().next());
        });
    });
</script>

<div class="panel-heading">График сна</div>

<div class="panel-body" style="padding-left: 20px">


    <?php
    // Настроейка шторма
    // ктрл + g =s перейти к строке,
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
    ?>


    <?php




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

    ?>

    <svg width="100%" height="1000" style="padding: 240px 0">

        <g transform="translate(40,20)">

            <g class="birthyears" transform="">

                @if($sleeps)
                    @foreach($sleeps as $key => $sleep)
                        {{--<div class="row blog-posts small post-item">--}}
                            <?php
                            $day = 24 * 60;
                            $half_day = 12 * 60;
                            $correction_hours = 0;
                            $chart_correction = $correction_hours * 60;
                            // Вводные: ночь понедельника является первым днем
                            // вс лег в 23 встал в пн в обед
                            // пн лег в 23, встал в 4 ночи
                            // вт лег за полночь в 1 ночи и проснулся в 15 в ср
                            // ср лег за полночь в 1 ночи и проснулся в 5 утра


                            // Получение даты сна
                            $dt_sleep_data = new DateTime($sleep->bedtime);
                            $sleep_data_day[] = (int)$dt_sleep_data->format('d');

                            // Преведение времяни отхода ко сну к объекту time
                            $dt_sleep_start = new DateTime($sleep->in_bed);
                            $text_top[$key] = $dt_sleep_start->format('H:i'); // Вывод текста времени отхода ко сну
                            $sleep_start_point = (int)$dt_sleep_start->format('i') + (int)$dt_sleep_start->format('H') * 60;                                         // Сумма в минутах, с начала суток до отхода ко сну

                            // Преведение времяни пробуждения к объекту time
                            $dt_sleep_end = new DateTime($sleep->awoke);
                            $text_bot[] = $dt_sleep_end->format('H:i'); // Отображение времени пробуждения
                            $sleep_end_point = (int)$dt_sleep_end->format('i') + (int)$dt_sleep_end->format('H') * 60; // Точка пробуждения, мин
                            $sleep_end_point_correction = $sleep_end_point + $chart_correction;




                            // Дэбаг данных
//                            echo '<h4>Итерация #'. ($key+1) .' ___ с '.$text_top[$key].' по '.$text_bot[$key].'</h4>';
//                            echo '$sleep_start_point = '.$sleep_start_point.' ';
//                            echo '<br>';
//                            echo '$sleep_end_point_correction = '.$sleep_end_point_correction.' ';
//                            echo '<br>';





                            if ($chart_correction == 0) {

                            // начальная точка отхода ко сну
                            $sleep_shape_first_position[] = $sleep_start_point;

                            if ($sleep_start_point < $sleep_end_point AND $sleep_start_point + $sleep_end_point > $day) {
//                            echo '<ul style="margin-bottom: 0;"><li>лег и проснулся до полуночи, </li></ul>';

                            // длина шейпа
                            $sleep_shape_first_height[] = $sleep_end_point  - $sleep_start_point;
                            $sleep_shape_second_height[] = '';

                            $text_second_bot_position[] = '-20';

                            // Кейс варианта отрисовки шейпа
                            $sleep_case[] = 1;
                            }

                            if ($sleep_start_point > $sleep_end_point ) {
//                            echo '<ul style="margin-bottom: 0;"><li>лег до полуночи, а проснулся после, </li></ul>';

                            // Первая половина шейпа этого дня, от начала сна и до конца суток
                            $sleep_shape_first_height[] = $day - $sleep_start_point;

                            // Вторая половина шейпа, от начала суток
                            $sleep_shape_second_height[] = $sleep_end_point / 3;

                            // Позиция текста для второй половины
                            $text_second_bot_position[$key] = $sleep_shape_second_height[$key] - 7;

//                            echo '$text_second_bot_position case2 = '.$text_second_bot_position[$key].' ';


                            $sleep_case[] = 2;

                            }

                            if ($sleep_start_point < $sleep_end_point AND $sleep_start_point + $sleep_end_point < $day) {
//                            echo '<ul style="margin-bottom: 0;"><li>лег после полуночи, </li></ul>';


                            // Нет шейпа для этого дня
                            $sleep_shape_first_height[] = 0;

                            // Вторая половина шейпа,
                            $sleep_shape_second_height[] = ($sleep_end_point - $sleep_start_point) / 3;
                            $text_second_bot_position[] = '-20';

//                            echo '$sleep_shape_second_height case3 = '.$sleep_shape_second_height[$key].' ';

                            $sleep_case[] = 3;

                            }



                            }




                            ?>
                        {{--</div>--}}
                    @endforeach
                @else
                    <p>Данные не найденны</p>
                @endif

                <?php
                /**
                 * Главная отрисовка шейпов сна
                 */

                $padding_start = 25;
                $padding_step = 60;

                for ($i=0; $i < 10; $i++) {

                    $text_first_top_position = $sleep_shape_first_position[$i] / 3 + 12.5;
                    $text_first_bot_position = $sleep_shape_first_position[$i] / 3 + $sleep_shape_first_height[$i] / 3 - 7.5;

                    $sleep_shape_first_position[$i] = $sleep_shape_first_position[$i] / 3;
                    $sleep_shape_first_height[$i] = $sleep_shape_first_height[$i] / 3;


                    echo '<g class="birthyear" transform="translate('.$padding_start.',0)">'; // группировка
                    echo   '<rect x="-23" width="46" y="0" height="480"></rect>'; // vertical background line

                    if($chart_correction == 0) {

                        if($sleep_case[$i] == 1) {
                            echo '<rect y="'.$sleep_shape_first_position[$i].'" x="-23" height="'.$sleep_shape_first_height[$i].'" style="fill:rgb(0,0,255);opacity:0.5" width="46" ></rect>';
                            echo '<text y="'.$text_first_top_position.'">'.$text_top[$i].'</text>';
                            echo '<text y="'.$text_first_bot_position.'">'.$text_bot[$i].'</text>';
                        }

                        if($sleep_case[$i] == 2) {


                            echo '<rect y="'.$sleep_shape_first_position[$i].'" x="-23" height="'.$sleep_shape_first_height[$i].'" style="fill:rgb(0,0,255);opacity:0.5" width="46"></rect>';
                            echo '<rect y="0" x="-23" height="'.$sleep_shape_second_height[$i].'" width="46" class="second-part-sleep3"></rect>';

                            echo '<text y="'.$text_first_top_position.'">'.$text_top[$i].'</text>';
                            echo '<text y="'.$text_second_bot_position[4].'" class="second-part-sleep3 case-2">'.$text_bot[$i].'</text>';

                        }

                        if($sleep_case[$i] == 3) {

                            $text_second_bot_position = $sleep_shape_second_height[$i] + $sleep_shape_first_position[$i] - 7;


                            echo '<rect y="'.$sleep_shape_first_position[$i].'" x="-23" height="'.$sleep_shape_second_height[$i].'" width="46" class="second-part-sleep3"></rect>';
                            echo '<text y="'.$text_first_top_position.'" class="second-part-sleep3 text-bot-case-3">'.$text_top[$i].'</text>';
                            echo '<text y="'.$text_second_bot_position.'" class="second-part-sleep3 text-top-case-3">'.$text_bot[$i].'</text>';


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
             * Отрисовка дней недели и чисел
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

