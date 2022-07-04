<?php

use App\Models\Language;

/**
 * Created by Artdevue.
 * User: artdevue - helpers.php
 * Date: 2020-01-06
 * Time: 13:25
 * Project: gamesgo.club
 */

/**
 * @param null $language_id
 * @return array
 */
function _lang($language_id = null)
{
    if (is_null($language_id)) {
        $language_id = Language::getMain()->id;
    }

    return \App\Models\Translation::rightJoin('translation_langs', 'translations.id', '=', 'translation_langs.translation_id')
        ->where('translation_langs.language_id', $language_id)->pluck('translation_langs.name', 'translations.key_lang');
}

/**
 * @param int $parent
 * @param int $lang
 * @return array
 */
function GenreTreeToSelect($parent = 0, $lang = 1)
{
    $array = [];

    $genre_all = \App\Models\Genre::all();
    foreach ($genre_all as $item) {
        $array[] = ['id' => $item->id, 'pid' => $item->pid, 'value' => $item->getName($lang)];
    }

    return buildTreeSelect($array, 'pid', 'id');
}

function getTree()
{

}

/**
 * @param $string
 * @return false|mixed|string|string[]|null
 */
function mb_ucfirst($string)
{
    return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
}

/**
 * @param      $flatStructure
 * @param      $pidKey
 * @param null $idKey
 * @return mixed
 */
function buildTreeSelect($flatStructure, $pidKey, $idKey = null)
{

    $buildTre = buildTree($flatStructure, 'pid', 'id');

    $buildSelect = '';
    foreach ($buildTre as $btr) {
        if (isset($btr['children'])) {
            $buildSelect .= '<optgroup label="' . $btr['value'] . '">';
            foreach ($btr['children'] as $btr1) {
                if (isset($btr1['children'])) {
                    $buildSelect .= '<optgroup label="' . $btr1['value'] . '">';
                    foreach ($btr1['children'] as $btr2) {
                        if (isset($btr2['children'])) {
                            $buildSelect .= '<optgroup label="' . $btr2['value'] . '">';
                            foreach ($btr2['children'] as $btr3) {
                                $buildSelect .= '<option value="' . $btr3['id'] . '">' . $btr3['value'] . ' </option>';
                            }
                            $buildSelect .= '</optgroup>';
                        } else {
                            $buildSelect .= '<option value="' . $btr2['id'] . '">' . $btr2['value'] . ' </option>';
                        }
                    }
                    $buildSelect .= '</optgroup>';
                } else {
                    $buildSelect .= '<option value="' . $btr1['id'] . '">' . $btr1['value'] . ' </option>';
                }
            }
            $buildSelect .= '</optgroup>';
        } else {
            $buildSelect .= '<option value="' . $btr['id'] . '">' . $btr['value'] . ' </option>';
        }
    }
    return $buildSelect;
}

/**
 * @param      $flatStructure
 * @param      $pidKey
 * @param null $idKey
 * @return mixed
 */
function buildTree($flatStructure, $pidKey, $idKey = null)
{
    $parents = [];

    foreach ($flatStructure as $item) {

        $parents[$item[$pidKey]][] = $item;
    }

    $fnBuilder = function ($items, $parents, $idKey) use (&$fnBuilder) {

        foreach ($items as $position => $item) {

            $id = $item[$idKey];

            if (isset($parents[$id])) { //is the parent set
                $item['children'] = $fnBuilder($parents[$id], $parents, $idKey); //add children
            }

            //reset the value as children have changed
            $items[$position] = $item;
        }

        //return the item
        return $items;
    };

    return $fnBuilder($parents[0], $parents, $idKey);
}

/**
 * @return array
 */
function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/OPR/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    } elseif (preg_match('/Edge/i', $u_agent)) {
        $bname = 'Edge';
        $ub = "Edge";
    } elseif (preg_match('/Trident/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }

    // finally get the correct version number
    $known = ['Version', $ub, 'other'];
    $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    return [
        'user_agent' => $u_agent,
        'name'       => $bname,
        'version'    => $version,
        'platform'   => $platform,
        'pattern'    => $pattern
    ];
}

/**
 * @return bool|string[]
 */
function isIphoneDevice()
{
    if(empty($_SERVER['HTTP_USER_AGENT'])) {
        return array(
            'name' => 'unrecognized',
            'version' => 'unknown',
            'platform' => 'unrecognized',
            'userAgent' => ''
        );
    }

    $aMobileUA = [
        '/iphone/i'     => 'iPhone',
        '/ipod/i'       => 'iPod',
        '/ipad/i'       => 'iPad',
    ];

    //Return true if Mobile User Agent is detected
    foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
        if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
    }
    //Otherwise return false..
    return false;
}

/**
 * @return bool
 */
function isMobileDevice()
{
    if(empty($_SERVER['HTTP_USER_AGENT'])) {
        return array(
            'name' => 'unrecognized',
            'version' => 'unknown',
            'platform' => 'unrecognized',
            'userAgent' => ''
        );
    }

    $aMobileUA = [
        '/iphone/i'     => 'iPhone',
        '/ipod/i'       => 'iPod',
        '/ipad/i'       => 'iPad',
        '/android/i'    => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i'      => 'Mobile'
    ];

    //Return true if Mobile User Agent is detected
    foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
        if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
    }
    //Otherwise return false..
    return false;
}

/**
 * @param int    $count
 * @param string $forms
 * @param int    $lang_id
 * @return string
 */
function plural(int $count, string $forms, int $lang_id = 1)
{
    if (!is_array($forms)) {
        $forms = explode('|', $forms);
    }
    $count = abs($count);
    if ($lang_id === 1) {
        $mod100 = $count % 100;
        switch ($count % 10) {
            case 1:
                if ($mod100 == 11) return $forms[2];
                else return $forms[0];
            case 2:
            case 3:
            case 4:
                if (($mod100 > 10) && ($mod100 < 20)) return $forms[2];
                else return $forms[1];
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 0:
                return $forms[2];
        }
    } else {
        /**
         * If lang not RU
         */
        return ($count == 1) ? $forms[0] : $forms[1];
    }
}

/**
 * Generating an array with pagination page numbers.
 *
 * @param int      $total
 * @param int|null $perPage
 * @param int|null $current
 * @param int|null $adjacentNum
 * @return array
 * @example:
 * $found_rows = 200;
 * $per_page = 10;
 * $current_page = 15;
 * $pages = generateSmartPaginationPageNumbers($found_rows, $per_page, $current_page, 5);
 *
 */
function generateSmartPaginationPageNumbers(int $total, int $perPage = null, int $current = null, int $adjacentNum = null): array
{
    $result = [];

    if (isset($total, $perPage) === true) {
        $result = range(1, ceil($total / $perPage));

        if (isset($current, $adjacentNum) === true) {
            if (($adjacentNum = floor($adjacentNum / 2) * 2 + 1) >= 1) {
                $result = array_slice($result, max(0, min(count($result) - $adjacentNum, intval($current) - ceil($adjacentNum / 2))), $adjacentNum);
            }
        }
    }

    if (!in_array(1, $result)) {
        if ($result[0] > 3) {
            array_unshift($result, '...' . ($result[0] - 1));
        }

        if ($result[0] == 3) {
            array_unshift($result, 2);
        }

        array_unshift($result, 1);
    }

    $totalPagesNum = ceil($total / $perPage);
    $lastMiddlePageNum = $result[count($result) - 1];
    if (!in_array($totalPagesNum, $result)) {
        if ($totalPagesNum - $lastMiddlePageNum > 2) {
            $result[] = '...' . ($lastMiddlePageNum + 1);
        }

        if ($totalPagesNum - $lastMiddlePageNum == 2) {
            $result[] = $lastMiddlePageNum + 1;
        }

        $result[] = $totalPagesNum;
    }

    return $result;
}