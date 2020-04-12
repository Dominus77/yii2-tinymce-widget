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
     * @param string $d1 Path From
     * @param string|bool $d2 Path to or result operation
     * @param bool $upd if update then true else false
     * @param bool $force if force then true else false
     */
    public function copyFolder($d1, $d2, $upd = true, $force = true)
    {
        if (is_dir($d1)) {
            $d2 = $this->mkdirSafe($d2, $force);
            if (!$d2) {
                Yii::debug("!!fail $d2", __METHOD__);
                return;
            }
            $d = dir($d1);
            while (false !== ($entry = $d->read())) {
                if ($entry !== '.' && $entry !== '..') {
                    self::copyFolder("$d1/$entry", "$d2/$entry", $upd, $force);
                }
            }
            $d->close();
        } else {
            $ok = $this->copyFile($d1, $d2, $upd);
            $ok = ($ok) ? 'ok-- ' : ' -- ';
            Yii::debug("{$ok}$d1", __METHOD__);
        }
    }

    /**
     * @param string $dir
     * @param bool $force
     * @return bool
     */
    private function mkdirSafe($dir = '', $force = false)
    {
        if (file_exists($dir)) {
            if (is_dir($dir)) {
                return $dir;
            }

            if (!$force) {
                return false;
            }
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
            if ($time2 >= $time1 && $upd) {
                return false;
            }
        }
        $ok = copy($f1, $f2);
        if ($ok) {
            touch($f2, $time1);
        }
        return $ok;
    }

    /**
     * Recursive chmod
     * @param string $path
     * @param int $mode
     * @return bool
     */
    public static function chmodR($path, $mode)
    {
        if (!is_dir($path)) {
            return chmod($path, $mode);
        }
        $dh = opendir($path);
        while ($file = readdir($dh)) {
            if ($file !== '.' && $file !== '..') {
                $fullPath = $path . '/' . $file;
                if (!is_dir($fullPath)) {
                    if (!chmod($fullPath, $mode)) {
                        return false;
                    }
                } else if (!self::chmodR($fullPath, $mode)) {
                    return false;
                }
            }
        }
        closedir($dh);
        if (chmod($path, $mode)) {
            return true;
        }
        return false;
    }
}
