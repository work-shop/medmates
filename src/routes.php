<?php

Routes::map("directory/:user_id", function ($params) {
  Routes::load("member.php", $params, null, 200);
});
