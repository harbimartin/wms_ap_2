<?php

namespace App\Http\Helper;

use App\Paletposkus;
use App\Poskus;
use App\Skus;
use App\Sos;
use Illuminate\Support\Facades\DB;

class Formz {

    public static function jrsnumber($name = null, $value = null) {
        return '<input type="number" name="' . $name . '" class="form-control input-xs" value="' . $value . '" />';
    }

    public static function jrsdate($name = null, $value = null) {
        return '<input type="date" name="' . $name . '" class="form-control input-xs" value="' . $value . '" />';
    }

    public static function jrsreadonly($name = null, $value = null) {
        return '<input type="text" name="' . $name . '" class="form-control input-xs" value="' . $value . '" readonly />';
    }

    public static function jrsselect($name = null, $list = null, $selected = null) {
        return Formz::select($name, $list, $selected, array('class' => 'form-control input-sm combobox'));
    }

    public static function jrssubmit($name = null, $type = null, $onclick = null) {
        ($onclick == TRUE)
            ? $oc = 'return doconfirm();'
            : $oc = NULL;
        return Formz::submit($name, array('class' => 'btn btn-' . $type . ' btn-sm', 'onClick' => $oc));
    }

    public static function jrstextarea($name = null, $value = null) {
        return Formz::textarea($name, $value, array('class' => 'form-control input-sm'));
    }

    public static function jrstext($name = null, $value = null) {
        return Formz::text($name, $value, array('class' => 'form-control input-sm'));
    }

    public static function jrspassword($name = null) {
        return Formz::password($name, array('class' => 'form-control input-sm'));
    }

    public static function skuByMnf($id_so) {
        $SkuLists = ['' => 'Select SKU'];
        $SOById = Sos::find($id_so);

        $skuByMnf = Skus::with('Uoms')->where('id_mnf', $SOById->id_mnf)->get();
        foreach ($skuByMnf as $sbm) {
            $poskusBySku = Poskus::where('id_sku', $sbm->id)->get();

            if (count($poskusBySku) > 0) {
                foreach ($poskusBySku as $pbs) {
                    $paletposkusByPoskus = Paletposkus::where('id_posku', $pbs->id)->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('tempstorages')
                            ->whereRaw('paletposkus.id = tempstorages.id_paletposku');
                    })->groupBy('id_posku')->sum('qty');
                    $ppbs = $paletposkusByPoskus;
                    $qty[$sbm->id] = $ppbs;
                }

                if ($qty[$sbm->id] != 0)
                    $SkuLists[$sbm->id] = $sbm->dsc . ' - ' . $qty[$sbm->id] . ' ' . $sbm->uoms->dsc;
            }
        }

        return $SkuLists;
    }

    public static function valSoDemand($id_so, $id_sku) {
        $SOById = Sos::find($id_so);

        $poskusBySku = Poskus::where('id_sku', $id_sku)->get();
        foreach ($poskusBySku as $pbs) {
            $paletposkusByPoskus = Paletposkus::where('id_posku', $pbs->id)->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tempstorages')
                    ->whereRaw('paletposkus.id = tempstorages.id_paletposku');
            })->groupBy('id_posku')->sum('qty');
            $ppbs = $paletposkusByPoskus;
        }

        $maxDemand = $ppbs;

        return $maxDemand;
    }

    public static function jrsopen($type, $url, $method) {
        $return = Formz::open(['url' => $type . '-' . $url, 'method' => $method]);
        return $return;
    }
    public static function open($param) {
        return '<form action="' . $param['url'] . '" method="' . $param['method'] . '">';
    }
    public static function select($name, $list, $selected, $class) {
        $ret = '<select name="' . $name . '">';
        foreach ($list as $kl => $l) {
            $ret .= '<option value="' . $kl . '" ' . ($selected == $kl ? 'selected' : '') . '>' . $l . '</option>';
        }
        $ret .= '</select>';
        return $ret;
    }

    public static function submit($name, $array) {
        return '<button type="submit" ' . (sizeof($array) > 0 ? ' class="' . implode($array) . '"' : '') . '>' . $name . '</button>';
    }

    public static function textarea($name = null, $value = null, $array) {
        return '<textarea name="' . $name . '" type="submit" ' . implode($array) . '>' . $value . '</textarea>';
    }

    public static function hidden($name, $value, $array = []) {
        return '<input hidden type="text" name="' . $name . '" value="' . $value . '" ' . implode($array) . '/>';
    }
    public static function text($name, $value, $array) {
        return '<input type="text" name="' . $name . '" value="' . $value . '" ' . implode($array) . '/>';
    }

    public static function password($name, $array) {
        return '<input type="password" name="' . $name . '" ' . implode($array) . '/>';
    }
    public static function close() {
        return '</form>';
    }
}
