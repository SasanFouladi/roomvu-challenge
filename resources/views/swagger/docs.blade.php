<html>
<head>
    <title> Roomvu Challenge - API Docs</title>
    <link href="/swagger/style.css" rel="stylesheet">
</head>
<body>
<div id="swagger-ui"></div>
<script src="/swagger/jquery-2.1.4.min.js"></script>
<script src="/swagger/swagger-bundle.js"></script>
<script type="application/javascript">
    const ui = SwaggerUIBundle({
        url: "{{ asset('swagger/v1.yaml') }}",
        dom_id: '#swagger-ui',
    });
</script>
</body>
</html>
