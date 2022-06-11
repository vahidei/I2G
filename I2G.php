<?php
class I2G
{
    private $img;
    private $callback = NULL;
    private $initialized = FALSE;

    protected $percent = 5;
    protected $steps = 10;

    public $w, $h;
    public $sample_w = 0;
    public $sample_h = 0;

    public function __construct($imagefile)
    {
        $info = getimagesize($imagefile);
        $extension = image_type_to_extension($info[2]);
        if ($extension == '.jpg' || $extension == '.jpeg') {
            if (!$this->img = imagecreatefromjpeg($imagefile)) {
                die("Error loading image: {$imagefile}");
            }
        } elseif ($extension == '.png') {

            if (!$this->img = imagecreatefrompng($imagefile)) {
                die("Error loading image: {$imagefile}");
            }
        }

        $this->w = imagesx($this->img);
        $this->h = imagesy($this->img);
    }

    public function setPercent($percent)
    {
        $percent = (int) $percent;
        if (($percent < 1) || ($percent > 50)) {
            die("Your \$percent value needs to be between 1 and 50.");
        }
        $this->percent = $percent;
    }

    public function setSteps($steps)
    {
        $steps = (int) $steps;
        if (($steps < 1) || ($steps > 50)) {
            die("Your \$steps value needs to be between 1 and 50.");
        }
        $this->steps = $steps;
    }

    private function setCallback($callback)
    {
        try {
            $fn = new \ReflectionFunction($callback);
            if ($fn->getNumberOfParameters() != 4) {
                throw new \ReflectionException("Invalid parameter count in callback function.  Usage: fn(int, int, int, bool) { ... }");
            }
            $this->callback = $callback;
        } catch (\ReflectionException $e) {
            die($e->getMessage());
        }
    }

    public function init()
    {
        $this->sample_w = $this->w / $this->steps;
        $this->sample_h = $this->h / $this->steps;
        $this->initialized = TRUE;
    }

    private function getPixelColor($x, $y)
    {
        $rgb = imagecolorat($this->img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        return [$r, $g, $b];
    }

    public function getGradients()
    {
        $this->setSteps(2);
        $this->init();
        $gradient = '';
        $i = 0;
        $sort = [135, 225, 45, 315];

        foreach ($this->sample() as $items) {
            foreach ($items as $item) {
                $gradient .= 'linear-gradient(' . $sort[$i++] . 'deg, rgb(' . $item[0] . ',' . $item[1] . ',' . $item[2] . '), rgba(0,0,0,0) 100% ),';
            }
        }
        return rtrim($gradient, ',');
    }

    public function sample($callback = NULL)
    {
        if (!$this->initialized) {
            $this->init();
        }
        if (($this->sample_w < 2) || ($this->sample_h < 2)) {
            die("Your sampling size is too small for this image - reduce the \$steps value.");
        }

        if ($callback) {
            $this->setCallback($callback);
        }

        $sample_size = round($this->sample_w * $this->sample_h * $this->percent / 100);

        $retval = [];
        for ($i = 0, $y = 0; $i < $this->steps; $i++, $y += $this->sample_h) {
            $flag = FALSE;
            $row_retval = [];
            for ($j = 0, $x = 0; $j < $this->steps; $j++, $x += $this->sample_w) {
                $total_r = $total_g = $total_b = 0;
                for ($k = 0; $k < $sample_size; $k++) {
                    $pixel_x = $x + rand(0, $this->sample_w - 1);
                    $pixel_y = $y + rand(0, $this->sample_h - 1);
                    list($r, $g, $b) = $this->getPixelColor($pixel_x, $pixel_y);
                    $total_r += $r;
                    $total_g += $g;
                    $total_b += $b;
                }
                $avg_r = round($total_r / $sample_size);
                $avg_g = round($total_g / $sample_size);
                $avg_b = round($total_b / $sample_size);
                if ($this->callback) {
                    call_user_func_array($this->callback, [$avg_r, $avg_g, $avg_b, !$flag]);
                }
                $row_retval[] = [$avg_r, $avg_g, $avg_b];
                $flag = TRUE;
            }
            $retval[] = $row_retval;
        }

        return $retval;
    }

}
