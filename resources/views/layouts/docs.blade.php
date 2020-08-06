<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- App Icon -->
    <link rel="icon"
          type="image/png"
          href="{{ asset('/storage/default/app-logo.png') }}">

    <link rel="stylesheet" href="//unpkg.com/docsify/themes/vue.css">
</head>
<body>
<div id="app"></div>
<script>
    window.$docsify = {
        loadSidebar: true
    }
</script>
<script src="//unpkg.com/docsify/lib/docsify.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-bash.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/prismjs@1/components/prism-php.min.js"></script>
</body>
</html>
