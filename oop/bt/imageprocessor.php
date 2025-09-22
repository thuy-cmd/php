<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image processor</title>
</head>

<body>
    <?php

    abstract class ImageProcessor {
        protected $imagePath;
        protected $resizedPath;

        // Chọn hình ảnh
        abstract public function selectImage(string $path);

        // Resize ảnh về 600x400
        abstract public function resize();

        // Export ảnh ra file mới
        abstract public function export(string $destination);

        // Tạo thẻ img hiển thị ảnh đã resize
        public function render(): string {
            return '<img src="' . $this->resizedPath . '" width="600" height="400" alt="Resized Image">';
        }
    }

    class JpegImage extends ImageProcessor {
        protected $imageResource;

        public function selectImage(string $path) {
            if (!file_exists($path)) {
                throw new Exception("File không tồn tại: $path");
            }
            $this->imagePath = $path;
            $this->imageResource = imagecreatefromjpeg($path);
            if (!$this->imageResource) {
                throw new Exception("Không thể tạo ảnh từ file JPEG");
            }
        }

        public function resize() {
            $width = 600;
            $height = 400;

            $resized = imagecreatetruecolor($width, $height);
            imagecopyresampled(
                $resized,
                $this->imageResource,
                0, 0, 0, 0,
                $width, $height,
                imagesx($this->imageResource),
                imagesy($this->imageResource)
            );

            imagedestroy($this->imageResource);
            $this->imageResource = $resized;
        }

        public function export(string $destination) {
            // Lưu ảnh jpeg với chất lượng 90
            if (!imagejpeg($this->imageResource, $destination, 90)) {
                throw new Exception("Không thể xuất file ảnh");
            }
            $this->resizedPath = $destination;
        }

        public function __destruct() {
            if ($this->imageResource) {
                imagedestroy($this->imageResource);
            }
        }
    }

    class PngImage extends ImageProcessor {
        protected $imageResource;

        public function selectImage(string $path) {
            if (!file_exists($path)) {
                throw new Exception("File không tồn tại: $path");
            }
            $this->imagePath = $path;
            $this->imageResource = imagecreatefrompng($path);
            if (!$this->imageResource) {
                throw new Exception("Không thể tạo ảnh từ file PNG");
            }
        }

        public function resize() {
            $width = 600;
            $height = 400;

            $resized = imagecreatetruecolor($width, $height);

            // Giữ lại kênh alpha cho PNG trong suốt
            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            imagecopyresampled(
                $resized,
                $this->imageResource,
                0, 0, 0, 0,
                $width, $height,
                imagesx($this->imageResource),
                imagesy($this->imageResource)
            );

            imagedestroy($this->imageResource);
            $this->imageResource = $resized;
        }

        public function export(string $destination) {
            if (!imagepng($this->imageResource, $destination)) {
                throw new Exception("Không thể xuất file ảnh");
            }
            $this->resizedPath = $destination;
        }

        public function __destruct() {
            if ($this->imageResource) {
                imagedestroy($this->imageResource);
            }
        }
    }

    class JpgImage extends JpegImage {
    }


    try {
        // $jpeg = new JpegImage();
        // $jpeg->selectImage('input.jpg');
        // $jpeg->resize();
        // $jpeg->export('resized_output.jpg');
        // echo $jpeg->render();

        // $png = new PngImage();
        // $png->selectImage('input.png');
        // $png->resize();
        // $png->export('resized_output.png');
        // echo $png->render();

        $jpg = new JpgImage();
        $jpg->selectImage('C:\Users\Administrator\Desktop\input.jpg');
        $jpg->resize();
        $jpg->export('resize-output.jpg');
        echo $jpg->render();

    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
    }
    ?>



</body>

</html>
