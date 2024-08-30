<!doctype html>
<?php echo $site->html ?>

<head>
  <meta name="google" content="notranslate" />
  <meta charset="utf-8" />
  <title><?php echo $site->title ?></title>
  <link rel="icon" type="image/png" href="<?php echo $site->favicon ?>" sizes="16x16">
  <!-- App favicon -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="<?php echo $site->description ?>" name="description" />
  <meta content="<?php echo $site->author ?>" name="author" />
  <?php set_meta() ?>
  <?php echo load_styles($site->head); ?>
  <style>
    [v-cloak] {
      display: none;
    }

    ::-webkit-scrollbar {
      width: 10px;
    }

    ::-webkit-scrollbar-thumb {
      background-color: #888;
    }

    ::-webkit-scrollbar-track {
      background-color: #f1f1f1;
    }

    .zoom {
      transition: transform .2s;
      cursor: pointer;
      z-index: 1;
      /* Initial z-index */
    }

    .zoom:hover {
      transform: scale(1.5);
      position: relative;
      z-index: 1000;
      /* Bring to front when clicked */
    }
  </style>
</head>

<body class="<?php echo $site->body_class ?>" style="<?php echo $site->body_style ?>">