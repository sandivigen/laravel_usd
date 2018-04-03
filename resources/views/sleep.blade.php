@extends('layouts.app')

@section('content')

<script>$( document ).ready(function() {
        $('.second-part-sleep3').each(function() {
            $(this).appendTo($(this).parent().next());
        });
    });
</script>

<div class="panel-heading">График сна</div>

<div class="panel-body" style="padding-left: 20px">






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

    ?>

    <svg width="100%" height="1000" style="padding: 240px 0">

        <g transform="translate(40,20)">

            <g class="birthyears" transform="">

                <?php
//                $padding_start = 25;
                $padding_start = -35;
                $padding_step = 60;
                ?>

                @if($sleeps)
                    @foreach($sleeps as $key => $sleep)
                        <?php


//



                        $day = 24 * 60;
                        $half_day = 12 * 60;
                        $correction_hours = 12;
                        $chart_correction = $correction_hours * 60;



                        // Получение даты сна
                        $dt_sleep_data = new DateTime($sleep->bedtime);
                        $sleep_data_day[] = (int)$dt_sleep_data->format('d');

                        // Преведение времяни отхода ко сну к объекту time
                        $dt_before_sleep_start = new DateTime($sleep->in_bed);
                        $text_before_sleep[] = $dt_before_sleep_start->format('H:i'); // Удалить, делаю для откладки
                        $before_sleep_start_point = (int)$dt_before_sleep_start->format('i') + (int)$dt_before_sleep_start->format('H') * 60;                                         // Сумма в минутах, с начала суток до отхода ко сну
                        $before_sleep_start_point_correction = $before_sleep_start_point + $chart_correction;

                        // Преведение времяни отхода ко сну к объекту time
                        $dt_sleep_start = new DateTime($sleep->asleep);
                        $text_top[] = $dt_sleep_start->format('H:i'); // Вывод текста времени отхода ко сну
                        $sleep_start_point = (int)$dt_sleep_start->format('i') + (int)$dt_sleep_start->format('H') * 60;                                         // Сумма в минутах, с начала суток до отхода ко сну
                        $sleep_start_point_correction = $sleep_start_point + $chart_correction;

                        // Преведение времяни пробуждения к объекту time
                        $dt_sleep_end = new DateTime($sleep->awoke);
                        $text_bot[] = $dt_sleep_end->format('H:i'); // Отображение времени пробуждения
                        $sleep_end_point = (int)$dt_sleep_end->format('i') + (int)$dt_sleep_end->format('H') * 60; // Точка пробуждения, мин
                        $sleep_end_point_correction = $sleep_end_point + $chart_correction;

                        // Преведение времяни поднялся с кровати к объекту time
                        $dt_after_sleep_end = new DateTime($sleep->get_up);
                        $text_after_sleep[] = $dt_after_sleep_end->format('H:i'); // Удалить, делаю для откладки
                        $after_sleep_end_point = (int)$dt_after_sleep_end->format('i') + (int)$dt_after_sleep_end->format('H') * 60;                                         // Сумма в минутах, с начала суток до отхода ко сну
                        $after_sleep_end_point_correction = $after_sleep_end_point + $chart_correction;


                        if ($chart_correction == 0) {
                            $timestamp_next_day = strtotime($sleep->bedtime);
                            $timestamp_next_day > time() ? $class_next_day = 'next-day' : $class_next_day = '';
                        }
                        if ($chart_correction != 0) {

                            $timestamp_next_day = strtotime($sleep->bedtime);
                            $timestamp_next_day > time() - 24*60*60 ? $class_next_day = 'next-day' : $class_next_day = '';

                        }


                        $weekend = date('N', strtotime($sleep->bedtime)) >= 6;
                        $weekend ? $weekend = 'class="bg-weekend-1"' : '';

                        echo '<g class="birthyear" transform="translate(' . $padding_start . ',0)">'; // группировка
                        echo    '<rect x="-23" width="46" y="0" height="480" '.$weekend.'></rect>'; // vertical background line


                        // вывод дат
                        if ($chart_correction == 0) {
                            $week_day_prev_str = strtotime('-1 day', strtotime($sleep->bedtime));
                            $week_day_prev = strftime("%a", $week_day_prev_str);
                            $num_day_prev_str = strtotime('-1 day', strtotime($sleep->bedtime));
                            $num_day_prev = strftime("%d", $num_day_prev_str);
                            $week_day = strftime("%a", strtotime($sleep->bedtime));
                            $num_day = strftime("%d", strtotime($sleep->bedtime));
                        }
                        if ($chart_correction != 0) {
                            $week_day_prev = strftime("%a", strtotime($sleep->bedtime));
                            $num_day_prev = strftime("%d", strtotime($sleep->bedtime));
                            $week_day_str = strtotime('+1 day', strtotime($sleep->bedtime));
                            $week_day = strftime("%a", $week_day_str);
                            $num_day_str = strtotime('+1 day', strtotime($sleep->bedtime));
                            $num_day = strftime("%d", $num_day_str);
                        }


                        echo '<text class="age" y="-30" dy=".71em">'.$week_day_prev.'</text>'; // дни недели верх
                        echo '<text class="age" y="-18" dy=".71em">'.$num_day_prev.'</text>'; // числа верх
                        echo '<text class="age" y="490" dy=".71em">'.$week_day.'</text>'; // дни недели
                        echo '<text class="age" y="502" dy=".71em">'.$num_day.'</text>'; // числа




//                            echo '<text class="TEST" y="550" fill="#000">' . $class_next_day . '</text>';


                        if ($chart_correction == 0) {

                            // -= 1 =-
                            //
                            // Лег и проснулся до полуночи
                            if ($sleep_start_point < $sleep_end_point AND $sleep_start_point + $sleep_end_point > $day) {


                                // Первая часть шейпа
                                $sleep_shape_first_height = ($sleep_end_point - $sleep_start_point) / 3;
                                $sleep_shape_first_position = $sleep_start_point / 3;

                                // Позиция текста
                                $text_first_top_position = $sleep_start_point / 3 + 12.5;
                                $text_first_bot_position = $sleep_start_point / 3 + $sleep_shape_first_height - 7.5;

                                echo '<rect y="' . $sleep_shape_first_position . '" x="-23" height="' . $sleep_shape_first_height . '" class="case-1-first '.$class_next_day.'" style="_fill:rgb(0,0,255);" width="46" ></rect>';

                                echo '<text y="' . $text_first_top_position . '">' . $text_top[$key] . '</text>';
                                echo '<text y="' . $text_first_bot_position . '">' . $text_bot[$key] . '</text>';

//                                echo '<text class="TEST" y="530" fill="#000">' . ($before_sleep_shape_first_height) . '</text>';
//                                echo '<text class="TEST" y="550" fill="#000">' . ($text_first_bot_position + 7.5 ) . '</text>';

                            }

                            // -= 2 =-
                            //
                            // Лег до полуночи, а проснулся после
                            if ($sleep_start_point > $sleep_end_point) {


                                
                                // Первая часть шейпа этого дня, от начала сна и до конца суток
                                $sleep_shape_first_height = ($day - $sleep_start_point) / 3;
                                $sleep_shape_first_position = $sleep_start_point / 3;

                                // Вторая половина шейпа, от начала суток
                                $sleep_shape_second_height = $sleep_end_point / 3;

                                // Позиция текста
                                $text_first_top_position = $sleep_start_point / 3 + 12.5;
                                $text_first_bot_position = $sleep_start_point / 3 + $sleep_shape_first_height / 3 - 7.5;
                                $text_second_bot_position = $sleep_shape_second_height - 7;


                                echo '<rect y="' . $sleep_shape_first_position . '" x="-23" height="' . $sleep_shape_first_height . '" class="case-2-first '.$class_next_day.'" _style="fill:rgb(0,0,255);opacity:0.5" width="46"></rect>';
                                echo '<rect y="0" x="-23" height="' . $sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-2-second '.$class_next_day.'"></rect>';

                                echo '<text y="' . $text_first_top_position . '">' . $text_top[$key] . '</text>';
                                echo '<text y="' . $text_second_bot_position . '" class="second-part-sleep3 case-2">' . $text_bot[$key] . '</text>';

                            }

                            // -= 3 =-
                            //
                            // Лег и проснулся после полуночи
                            if ($sleep_start_point < $sleep_end_point AND $sleep_start_point + $sleep_end_point < $day) {

                                // Вторая часть шейпа
                                $sleep_shape_second_height = ($sleep_end_point - $sleep_start_point) / 3;
                                $sleep_shape_second_position = $sleep_start_point / 3;

                                // Позиция текста
                                $text_second_top_position = $sleep_start_point / 3 + 12.5;
                                $text_second_bot_position = $sleep_shape_second_height + $sleep_start_point / 3 - 7;

                                echo '<rect y="' . $sleep_shape_second_position . '" x="-23" height="' . $sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-3-second '.$class_next_day.'a"></rect>';

                                echo '<text y="' . $text_second_top_position . '" class="second-part-sleep3 text-bot-case-3">' . $text_top[$key] . '</text>';
                                echo '<text y="' . $text_second_bot_position . '" class="second-part-sleep3 text-top-case-3">' . $text_bot[$key] . '</text>';

//                                echo '<text class="TEST" y="650" fill="#000">' . $sleep_shape_second_height . '</text>';
//                                echo '<text class="TEST" y="675" fill="#000">' . ($sleep_start_point /3) . '</text>';

                            }


                            // -= 1.a =- Время до засыпания
                            //
                            // Лег и проснулся до полуночи
                            if ($before_sleep_start_point < $sleep_start_point AND $before_sleep_start_point + $sleep_start_point > $day) {

                                // Шейп перед сном
                                $before_sleep_shape_first_height = ($sleep_start_point - $before_sleep_start_point) / 3;
                                $before_sleep_shape_first_position = $before_sleep_start_point / 3;

                                echo '<rect y="' . $before_sleep_shape_first_position . '" x="-23" height="' . $before_sleep_shape_first_height . '" class="case-1-first before-sleep" width="46" ></rect>';


//                                echo '<text class="TEST" y="530" fill="#000">' . 'перед' . '</text>';

                            }

                            // -= 2.a =-
                            //
                            // Лег до полуночи, а проснулся после
                            if ($before_sleep_start_point > $sleep_start_point) {

                                // Шейп перед сном
                                $before_sleep_shape_first_height = ($day - $before_sleep_start_point) / 3;
                                $before_sleep_shape_first_position = $before_sleep_start_point / 3;

                                // Вторая половина шейпа, от начала суток
                                $before_sleep_shape_second_height = $sleep_start_point / 3;

                                echo '<rect y="' . $before_sleep_shape_first_position . '" x="-23" height="' . $before_sleep_shape_first_height . '" class="case-2-first before-sleep '.$class_next_day.'" _style="fill:rgb(0,0,255);opacity:0.5" width="46"></rect>';
                                echo '<rect y="0" x="-23" height="' . $before_sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-2-second before-sleep '.$class_next_day.'"></rect>';


//                                echo '<text class="TEST" y="530" fill="#000">' . 'разделен' . '</text>';

                            }

                            // -= 3.a =-
                            //
                            // Лег и проснулся после полуночи
                            if ($before_sleep_start_point < $sleep_start_point AND $before_sleep_start_point + $sleep_start_point < $day) {

                                // Вторая часть шейпа
                                $before_sleep_shape_first_height = ($sleep_start_point - $before_sleep_start_point) / 3;
                                $before_sleep_shape_first_position = $before_sleep_start_point / 3;

                                echo '<rect y="' . $before_sleep_shape_first_position . '" x="-23" height="' . $before_sleep_shape_first_height . '" width="46" class="second-part-sleep3 before-sleep case-3-second '.$class_next_day.'a"></rect>';

//                                echo '<text class="TEST" y="530" fill="#000">' . 'посл' . '</text>';
                            }


                            // -= 1.b =- Время до поднятия с кровати
                            //
                            // Лег и проснулся до полуночи
                            if ($sleep_end_point < $after_sleep_end_point AND $sleep_end_point + $after_sleep_end_point > $day) {

                                // Шейп перед сном
                                $after_sleep_shape_first_height = ($after_sleep_end_point - $sleep_end_point) / 3;
                                $after_sleep_shape_first_position = $sleep_end_point / 3;

                                echo '<rect y="' . $after_sleep_shape_first_position . '" x="-23" height="' . $after_sleep_shape_first_height . '" class="case-1-first before-sleep" width="46" ></rect>';

//                                echo '<text class="TEST" y="530" fill="#000">' . 'перед' . '</text>';

                            }

                            // -= 2.b =-
                            //
                            // Лег до полуночи, а проснулся после
                            if ($sleep_end_point > $after_sleep_end_point) {

                                // Шейп перед сном
                                $after_sleep_shape_first_height = ($day - $sleep_end_point) / 3;
                                $after_sleep_shape_first_position = $sleep_end_point / 3;

                                // Вторая половина шейпа, от начала суток
                                $after_sleep_shape_second_height = $after_sleep_end_point / 3;

                                echo '<rect y="' . $after_sleep_shape_first_position . '" x="-23" height="' . $after_sleep_shape_first_height . '" class="case-2-first before-sleep '.$class_next_day.'" _style="fill:rgb(0,0,255);opacity:0.5" width="46"></rect>';
                                echo '<rect y="0" x="-23" height="' . $after_sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-2-second before-sleep '.$class_next_day.'"></rect>';


//                                echo '<text class="TEST" y="530" fill="#000">' . 'разделен' . '</text>';

                            }

                            // -= 3.b =-
                            //
                            // Лег и проснулся после полуночи
                            if ($sleep_end_point < $after_sleep_end_point AND $sleep_end_point + $after_sleep_end_point < $day) {

                                // Вторая часть шейпа
                                $after_sleep_shape_second_height = ($after_sleep_end_point - $sleep_end_point) / 3;
                                $after_sleep_shape_first_position = $sleep_end_point / 3;

                                echo '<rect y="' . $after_sleep_shape_first_position . '" x="-23" height="' . $after_sleep_shape_second_height . '" width="46" class="second-part-sleep3 before-sleep case-3-second '.$class_next_day.'a"></rect>';

//                                echo '<text class="TEST" y="530" fill="#000">' . 'посл' . '</text>';
                            }


                        }

                        if ($chart_correction != 0) {

                            // -= 1 =-
                            //
                            // Лег и проснулся до полуночи
                            if ($sleep_start_point_correction < $chart_correction + $day AND $sleep_end_point_correction < $day) {

//                                echo '<text class="TEST" y="730" fill="#000">' . ($chart_correction /3 + $day /3) . '</text>';
//                                echo '<text class="TEST" y="770" fill="#000">' . ($sleep_end_point_correction /3 ) . '</text>';

                                // Первая часть шейпа
                                $sleep_shape_first_height = ($sleep_end_point_correction - $sleep_start_point_correction) / 3;
                                $sleep_shape_first_position = $sleep_start_point_correction / 3;

                                // Если лег до полуночи, то не надо отнимать время корекции
                                if($sleep_shape_first_height < 0) $sleep_shape_first_height = ($sleep_end_point_correction - $sleep_start_point_correction) / 3 + $day / 3;

                                // Если начальная позиция выходит за график (с учетом коррекции), то необходимо сдвинуть шейп вверх
                                if ($sleep_shape_first_position > $day / 3 ) $sleep_shape_first_position = $sleep_shape_first_position - $day / 3;


                                // Позиция текста
                                $text_first_top_position = $sleep_start_point_correction / 3 + 12.5;
                                $text_first_bot_position = $sleep_start_point_correction / 3 + $sleep_shape_first_height - 7.5;
                                // Если начальная позиция выходит за график (с учетом коррекции), то необходимо сдвинуть шейп вверх
                                if ($text_first_top_position > $day / 3 ) $text_first_top_position = $text_first_top_position - $day / 3;
                                if ($text_first_bot_position > $day / 3 ) $text_first_bot_position = $text_first_bot_position - $day / 3;


                                echo '<rect y="' . $sleep_shape_first_position . '" x="-23" height="' . $sleep_shape_first_height . '" class="case-1-first '.$class_next_day.'" style="_fill:rgb(0,0,255);" width="46" ></rect>';

                                echo '<text y="' . $text_first_top_position . '">' . $text_top[$key] . '</text>';
                                echo '<text y="' . $text_first_bot_position . '">' . $text_bot[$key] . '</text>';

//                                echo '<text class="TEST" y="530" fill="#000">' . 'перед' . '</text>';
//                                echo '<text class="TEST" y="690" fill="#000">st ' . ($sleep_start_point_correction /3 ) . '</text>';
//                                echo '<text class="TEST" y="710" fill="#000">fn ' . ($sleep_end_point_correction /3 ) . '</text>';


                            }

                            // -= 2 =-
                            //
                            // Лег до полуночи, а проснулся после
                            if ($sleep_end_point_correction > $day) {

                                // Первая часть шейпа
                                $sleep_shape_first_height = ($day - $sleep_start_point_correction) / 3;
                                $sleep_shape_first_position = $sleep_start_point_correction / 3;


                                // Вторая половина шейпа, от начала суток
                                $sleep_shape_second_height = $sleep_end_point / 3 - $chart_correction / 3;
                                $sleep_shape_second_position = $sleep_start_point_correction / 3;



                                // Позиция текста
                                $text_first_top_position = $sleep_start_point_correction / 3 + 12.5;
//                                if ($text_first_top_position > $day / 3 ) $text_first_top_position = $text_first_top_position - $day / 3;
                                $text_second_bot_position = $sleep_shape_second_height - 7;


                                echo '<rect y="' . $sleep_shape_first_position . '" x="-23" height="' . $sleep_shape_first_height . '" class="case-2-first '.$class_next_day.'" _style="fill:rgb(0,0,255);opacity:0.5" width="46"></rect>';
                                echo '<rect y="0" x="-23" height="' . $sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-2-second '.$class_next_day.'"></rect>';
//                                echo '<rect y="' . $sleep_shape_second_position . '" x="-23" height="' . $sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-2-second '.$class_next_day.'"></rect>';

                                echo '<text y="' . $text_first_top_position . '">' . $text_top[$key] . '</text>';
                                echo '<text y="' . $text_second_bot_position . '" class="second-part-sleep3 case-2">' . $text_bot[$key] . '</text>';

                                echo '<text class="TEST" y="530" fill="#000">' . 'разд' . '</text>';
                                echo '<text class="TEST" y="690" fill="#000">st ' . ($sleep_start_point_correction /3 ) . '</text>';
                                echo '<text class="TEST" y="710" fill="#000">fn ' . ($sleep_end_point_correction /3 ) . '</text>';

                            }

                            // -= 3 =-
                            //
                            // Лег до полуночи, а проснулся после
                            if (false) {

                                // если скорректированные время до сна больше времени начала сна, значит перенос
                                // все равно будет костыль, ведь если время до сна не будет делиться, то вычеслить по прежнему не удатся
                                // конечно можно сделать и это 50% работы, но не охото делать что-попало
                                // посмотрю как будет себя висти на практике, возможно можно будет обойтись таким решением и придется сделать
                                // но пока не хочу, луше к датам привязываться или лучше сделать второй сон(допустим в обед - дневной)
                                // тогда это будет правильное решение
//
//
//                                // Первая часть шейпа
//                                $sleep_shape_first_height = ($day - $sleep_start_point_correction) / 3;
//                                $sleep_shape_first_position = $sleep_start_point_correction / 3;
//
//
//                                // Вторая половина шейпа, от начала суток
//                                $sleep_shape_second_height = $sleep_end_point / 3 - $chart_correction / 3;
//
//                                // Позиция текста
//                                $text_first_top_position = $sleep_start_point_correction / 3 + 12.5;
////                                if ($text_first_top_position > $day / 3 ) $text_first_top_position = $text_first_top_position - $day / 3;
//
//                                $text_second_bot_position = $sleep_shape_second_height - 7;
//
//
//                                echo '<rect y="' . $sleep_shape_first_position . '" x="-23" height="' . $sleep_shape_first_height . '" class="case-2-first '.$class_next_day.'" _style="fill:rgb(0,0,255);opacity:0.5" width="46"></rect>';
//                                echo '<rect y="0" x="-23" height="' . $sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-2-second '.$class_next_day.'"></rect>';
//
//                                echo '<text y="' . $text_first_top_position . '">' . $text_top[$key] . '</text>';
//                                echo '<text y="' . $text_second_bot_position . '" class="second-part-sleep3 case-2">' . $text_bot[$key] . '</text>';
//
//                                echo '<text class="TEST" y="530" fill="#000">' . 'разд' . '</text>';

                            }


                            // -= 1a =-
                            //
                            // Лег и проснулся до полуночи
                            if ($before_sleep_start_point_correction < $chart_correction + $day AND $sleep_start_point_correction < $day + $chart_correction) {


                                echo '<text class="TEST" y="730" fill="#000">' . ($chart_correction /3 + $day /3) . '</text>';
//                                echo '<text class="TEST" y="770" fill="#000">' . ($sleep_end_point_correction /3 ) . '</text>';

                                // Первая часть шейпа
                                $sleep_shape_first_height = ($sleep_start_point_correction - $before_sleep_start_point_correction) / 3;
                                $sleep_shape_first_position = $before_sleep_start_point_correction / 3;

                                // Если лег до полуночи, то не надо отнимать время корекции
                                if($sleep_shape_first_height < 0) $sleep_shape_first_height = ($sleep_start_point_correction - $before_sleep_start_point_correction) / 3 + $day / 3;

                                // Если начальная позиция выходит за график (с учетом коррекции), то необходимо сдвинуть шейп вверх
                                if ($sleep_shape_first_position > $day / 3 ) $sleep_shape_first_position = $sleep_shape_first_position - $day / 3;

                                if ($sleep_end_point == $after_sleep_end_point) { // пока костыль(что-то съехало), но если по сути нет после сна то и не выводить, пока делаю для след-х дней планирования
                                    $sleep_shape_first_height = 0;
                                }

                                echo '<rect y="' . $sleep_shape_first_position . '" x="-23" height="' . $sleep_shape_first_height . '" class="case-1-first before-sleep '.$class_next_day.'" style="_fill:rgb(0,0,255);" width="46" ></rect>';


//                                echo '<text class="TEST" y="530" fill="#000">' . 'перед' . '</text>';
//                                echo '<text class="TEST" y="690" fill="#000">bef st ' . ($before_sleep_start_point_correction /3 ) . '</text>';
//                                echo '<text class="TEST" y="710" fill="#000">st ' . ($sleep_start_point_correction /3 ) . '</text>';


                            }

                            // -= 2a =-
                            //
                            // Лег до полуночи, а проснулся после
//                            if ($sleep_end_point_correction > $day) {
                            if ($before_sleep_start_point_correction < $chart_correction + $day AND  $sleep_start_point_correction > $day + $chart_correction) {

                                // Первая часть шейпа
                                $sleep_shape_first_height = ($day - $sleep_start_point_correction) / 3;
                                $sleep_shape_first_position = $sleep_start_point_correction / 3;


                                // Вторая половина шейпа, от начала суток
                                $sleep_shape_second_height = $sleep_end_point / 3 - $chart_correction / 3;

                                // Позиция текста
                                $text_first_top_position = $sleep_start_point_correction / 3 + 12.5;
//                                if ($text_first_top_position > $day / 3 ) $text_first_top_position = $text_first_top_position - $day / 3;

                                $text_second_bot_position = $sleep_shape_second_height - 7;



                                echo '<rect y="' . $sleep_shape_first_position . '" x="-23" height="' . $sleep_shape_first_height . '" class="case-2-first '.$class_next_day.'" _style="fill:rgb(0,0,255);opacity:0.5" width="46"></rect>';
                                echo '<rect y="0" x="-23" height="' . $sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-2-second '.$class_next_day.'"></rect>';

                                echo '<text y="' . $text_first_top_position . '">' . $text_top[$key] . '</text>';
                                echo '<text y="' . $text_second_bot_position . '" class="second-part-sleep3 case-2">' . $text_bot[$key] . '</text>';

                                echo '<text class="TEST" y="530" fill="#000">' . 'разд' . '</text>';

                            }

                            // -= 1.b =- Время до поднятия с кровати
                            //
                            // Лег и проснулся до полуночи
                            if ($sleep_start_point_correction < $chart_correction + $day AND $sleep_end_point_correction < $day) {

                                // Шейп перед сном
                                $after_sleep_shape_first_height = ($after_sleep_end_point_correction - $sleep_end_point_correction) / 3;
                                $after_sleep_shape_first_position = $sleep_end_point_correction / 3;

//                                if ($sleep_end_point == $after_sleep_end_point) { // пока костыль(что-то съехало), но если по сути нет после сна то и не выводить, пока делаю для след-х дней планирования
//                                    $after_sleep_shape_first_height = 0;
//                                }
                                echo '<rect y="' . $after_sleep_shape_first_position . '" x="-23" height="' . $after_sleep_shape_first_height . '" class="case-1-first after-sleep" width="46" ></rect>';

//                                echo '<text class="TEST" y="530" fill="#000">' . 'перед' . '</text>';

                            }

                            // -= 2.b =-
                            //
                            // Лег до полуночи, а проснулся после
                            if ($sleep_end_point_correction > $day) {

                                // Шейп перед сном
//                                $after_sleep_shape_first_height = ($day - $sleep_end_point_correction - $chart_correction) / 3; // покубираю, так как перенос не делаю
//                                $after_sleep_shape_first_position = $sleep_end_point_correction / 3;

                                // Вторая половина шейпа, от начала суток
                                $after_sleep_shape_second_height = ($after_sleep_end_point_correction - $sleep_end_point_correction) / 3;
                                $after_sleep_shape_second_position = ($sleep_end_point_correction - $day) / 3;

//                                if ($sleep_end_point == $after_sleep_end_point) { // пока костыль(что-то съехало), но если по сути нет после сна то и не выводить, пока делаю для след-х дней планирования
//                                    $after_sleep_shape_second_height = 0;
//                                }


//                                echo '<rect y="' . $after_sleep_shape_first_position . '" x="-23" height="' . $after_sleep_shape_first_height . '" class="case-2-first before-sleep '.$class_next_day.'" _style="fill:rgb(0,0,255);opacity:0.5" width="46"></rect>';
                                echo '<rect y="'.$after_sleep_shape_second_position.'" x="-23" height="' . $after_sleep_shape_second_height . '" width="46" class="second-part-sleep3 case-2-second after-sleep  '.$class_next_day.'"></rect>';


//                                echo '<text class="TEST" y="530" fill="#000">' . 'разделен' . '</text>';

                            }

                        }

                        echo '<text class="TEST" y="585" fill="#000">' . $text_before_sleep[$key] . '</text>';
                        echo '<text class="TEST" y="600" fill="#000">' . $text_top[$key] . '</text>';
//                        echo '<text class="TEST" y="615" fill="#000">' . $text_bot[$key] . '</text>';
//                        echo '<text class="TEST" y="630" fill="#000">' . $text_after_sleep[$key] . '</text>';

                        $padding_start = $padding_start + $padding_step;

                        echo '</g>';

                        ?>
                    @endforeach
                @else
                    <p>Данные не найденны</p>
                @endif

                <?php
                    if ($chart_correction == 0) {
                        echo '<g class="birthyear" transform="translate(' . $padding_start . ',0)">'; // группировка
                        echo    '<rect x="-23" width="46" y="0" height="480" ></rect>'; // vertical background line
                        echo '</g>';
                    }
                ?>

            </g>


            <g class="y axis" transform="translate(900,0)">


                <?php

                /**
                 * Отрисовка линий часов
                 */

                $step_start = 0;
                $step = 20;
//                $correction_hours = 0;

                for ($i=0; $i <= 24; $i++) {

                    if ($i == 24) {
                        echo '<g class="tick zero time-line" transform="translate(0,'.$step_start.')" style="opacity: 1;">';
                    } else {
                        echo '<g class="tick time-line" transform="translate(0,'.$step_start.')" style="opacity: 1;">';
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



            ?>

        </g>
    </svg>
</div>




@endsection

