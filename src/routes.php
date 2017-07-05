<?php

Routes::map("directory", function () {
  Routes::load("directory.php", null, null, 200);
});

Routes::map("directory/:user_id", function ($params) {
  Routes::load("member.php", $params, null, 200);
});
