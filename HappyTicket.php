<?php

class HappyTicket
{
    const VALIDATION_DATA_ERROR = 'Validation error';
    const INVALID_VALUES_DATA = 'Choose a different range';

    /**
     * @return bool|string
     */
    //Базовый вариант получения количества счастливых билетов,
    //проверка по всему диапазону чисел, минус в быстродействии скрипта
    public static function getCountHappyTicketsAlternative()
    {
        $min = (int) $_POST['min'];
        $max = (int) $_POST['max'];

        if(!is_int($min) || !is_int($max))
            return self::returnFailResult(self::VALIDATION_DATA_ERROR);

        if(($min < 100000 || $min > 999999) || ($max < $min || $max > 999999))
            return self::returnFailResult(self::INVALID_VALUES_DATA);

        $result = 0;
        for ($i = $min; $i <= $max; $i++) {
            $number_first = substr($i, 0, 3);
            $number_last = substr($i, 3, 6);
            $first_summ = array_sum(str_split($number_first));
            $second_summ = array_sum(str_split($number_last));
            if ($first_summ == $second_summ) {
                $result++;
            }
        }
        return self::returnSuccessResult($result);
    }

    /**
     * @return bool|string
     */
    //Данный вариант итерирует в 1000 раз меньше значений в диапазоне чисел
    //Минус, в наличии погрешности
    public static function getCountHappyTickets()
    {
        $min = (int) $_POST['min'];
        $max = (int) $_POST['max'];

        if(!is_int($min) || !is_int($max))
            return self::returnFailResult(self::VALIDATION_DATA_ERROR);

        if(($min < 100000 || $min > 999999) || ($max < $min || $max > 999999))
            return self::returnFailResult(self::INVALID_VALUES_DATA);

        $arr = [];
        for ($i = 0; $i < 10; $i++) {
            $arr[] = 1;
        }

        for ($i = 0; $i < 2; $i++) {
            $count_elements = count($arr) + 9;
            $new_arr = [];
            for ($k = 0; $k < $count_elements; $k++) {
                $q = 0;
                for ($j = 0; $j < 10; $j++) {
                    if (@$arr[$k - $j]) {
                        $q += $arr[$k - $j];
                        $new_arr[$k] = $q;
                    }
                }
            }
            $arr = $new_arr;
        }

        $result = 0;
        for ($i = $min; $i < $max; $i += 1000) {
            $number_first = substr($i, 0, 3);
            $summ = array_sum(str_split($number_first));
            $result += $arr[$summ];
        }
        return self::returnSuccessResult($result);
    }

    /**
     * @param $result
     * @return false|string
     */
    public static function returnSuccessResult($result)
    {
        $options = [
            'success' => true,
            'countHappyTickets' => $result,
        ];

        return json_encode($options);
    }

    /**
     * @return false|string
     */
    public static function returnFailResult($msg)
    {
        $options = [
            'success' => false,
            'message' => $msg,
        ];

        return json_encode($options);
    }

}

if(!empty($_POST) && key_exists('min', $_POST) && key_exists('max',$_POST)) {
    print HappyTicket::getCountHappyTickets();
} else {
    var_dump($_POST);
    print HappyTicket::returnFailResult(HappyTicket::VALIDATION_DATA_ERROR);
}