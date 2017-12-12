{__NOLAYOUT__}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>跳转提示</title>
  <style type="text/css">
    * {
      padding: 0;
      margin: 0;
    }
    /*body{ background: #fff; font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif; color: #333; font-size: 16px; }*/

    body {
      background: #d2f4f3;
      font-family: "Microsoft Yahei", "Helvetica Neue", Helvetica, Arial, sans-serif;
      color: #333;
      font-size: 16px;
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
    }
    /*.system-message{ padding: 24px 48px; }*/

    .system-message {
      padding: 24px 48px;
      width: 50%;
      height: 50%;
      margin: 10% auto;
      background: #fff;
      box-shadow: 5px 5px 5px #ccc;
    }

    .system-message h1 {
      font-size: 100px;
      font-weight: normal;
      line-height: 120px;
      margin-bottom: 12px;
    }

    .system-message .jump {
      padding-top: 20px;
      text-align: center;
      color: #888;
    }

    .system-message .jump a {
      color: #f00;
    }

    .system-message .success,
    .system-message .error {
      /*line-height: 1.8em;*/
      font-size: 28px;
      text-align: center;
      border-bottom: 1px solid #ccc;
      height: 50%;
      line-height: 100px;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }

    .system-message .detail {
      font-size: 12px;
      line-height: 20px;
      margin-top: 12px;
      display: none;
    }
  </style>
</head>

<body>

  <div class="system-message">
    <?php switch ($code) {?>
    <?php case 1:?>
    <p class="success">
      <?php echo(strip_tags($msg));?>
    </p>
    <?php break;?>
    <?php case 0:?>
    <p class="error">
      <?php echo(strip_tags($msg));?>
    </p>
    <?php break;?>
    <?php } ?>
    <p class="detail"></p>
    <p class="jump">
      <!-- 页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b> -->
      页面自动 <a id="href" href="<?php echo($url);?>">跳转</a><br /> 等待时间： <b id="wait">99</b>
    </p>
  </div>
  <script type="text/javascript">
    (function() {
      var wait = document.getElementById('wait'),
        href = document.getElementById('href').href;
      var interval = setInterval(function() {
        var time = --wait.innerHTML;
        if (time <= 0) {
          location.href = href;
          clearInterval(interval);
        };
      }, 1000);
    })();
  </script>
</body>

</html>
