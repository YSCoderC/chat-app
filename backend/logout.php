<?php

session_unset();
session_destroy();

header('Location: /chat_app/registration/login.html');