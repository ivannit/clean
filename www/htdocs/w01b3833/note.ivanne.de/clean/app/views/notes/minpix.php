<?php

header('Content-type: image/' . $data['pixtype']);

resizePicture($data['pixdata'], $data['pixtype'], 10);
