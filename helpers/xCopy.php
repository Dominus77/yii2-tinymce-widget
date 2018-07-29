<?php

namespace dominus77\tinymce\helpers;

use Yii;

/**
 * Class xCopy
 * @package dominus77\tinymce\helpers
 */
class xCopy
{
    /**
     * @param $d1
     * @param $d2
     * @param bool $upd
     * @param bool $force
     */
    public function copyFolder($d1, $d2, $upd = true, $force = true)
    {
        if (is_dir($d1)) {
            $d2 = self::mkdir_safe($d2, $force);
            if (!$d2) {
                /** @deprecated since 2.0.14. Use [[debug()]] instead. */
                Yii::trace("!!fail $d2", __METHOD__);
                return;
            }
            $d = dir($d1);
            while (false !== ($entry = $d->read())) {
                if ($entry != '.' && $entry != '..')
                    self::copyFolder("$d1/$entry", "$d2/$entry", $upd, $force);
            }
            $d->close();
        } else {
            $ok = self::copyFile($d1, $d2, $upd);
            $ok = ($ok) ? "ok-- " : " -- ";
            /** @deprecated since 2.0.14. Use [[debug()]] instead. */
            Yii::trace("{$ok}$d1", __METHOD__);
        }
    }

    /**
     * @param $dir
     * @param $force
     * @return bool
     */
    private function mkdir_safe($dir, $force)
    {
        if (file_exists($dir)) {
            if (is_dir($dir)) return $dir;
            else if (!$force) return false;
            unlink($dir);
        }
        return (mkdir($dir, 0777, true)) ? $dir : false;
    }

    /**
     * @param $f1
     * @param $f2
     * @param $upd
     * @return bool
     */
    private function copyFile($f1, $f2, $upd)
    {
        $time1 = filemtime($f1);
        if (file_exists($f2)) {
            $time2 = filemtime($f2);
            if ($time2 >= $time1 && $upd) return false;
        }
        $ok = copy($f1, $f2);
        if ($ok) touch($f2, $time1);
        return $ok;
    }
}
