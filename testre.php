<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>URL Rewrite Module Test</title>
</head>
<body>
      <h1>URL Rewrite Module Test Page</h1>
      <table>
            <tr>
                  <th>Server Variable</th>
                  <th>Value</th>
            </tr>
            <tr>
                  <td>Original URL: </td>
                  <td><?php echo $_SERVER["HTTP_X_ORIGINAL_URL"]; ?></td>
            </tr>
            <tr>
                  <td>Final URL: </td>
                  <td><?php echo $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"]; ?></td>
            </tr>
      </table>
</body>
</html>
