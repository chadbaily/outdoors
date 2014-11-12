<form action="db.php" method="POST">
    <table><tr><td></td><td>
    <input type="text" size=60 name="filename" value="<?php echo $_POST['filename']; ?>">
    </tr>
<?php

# The prefix for database table names.
$cfg['table_prefix'] = "m_";

# How to connect to the database.
$cfg['db'] = array(
    'type' => 'MySqlConnection',
    'persistent' => true,
    'user' => 'at_w',
    'pass' => 'At_w_12pass',
    'db' => 'at_main1',
    'host' => 'db57b.pair.com',
    'debug' => true,
    'dump' => true,
    'errlevel' => E_USER_ERROR,
    'prefix' => $cfg['table_prefix']);

# ------------------------------------------------------------------------------
# Create the database connection.
# ------------------------------------------------------------------------------
include_once("{$cfg['db']['type']}.php");
$obj['conn'] =& new $cfg['db']['type']($cfg['db']);
$obj['conn']->open();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sub = false;
    $file = file_get_contents($_POST['filename']);
    $params = array_unique($obj['conn']->getRawParams($file));
    foreach ($params as $param) {
        $sub = $sub || $_POST[$param];
        echo "<tr><td>$param</td><td>"
            . "<input size=60 type=text name=$param value=\""
            . htmlspecialchars($_POST[$param])
            . "\"></td></tr>";
    }
}
?>
    </table>
    <input type="submit">
    <input type="checkbox" value="1" name="__"> Show query
</form>
<hr>

<?php
if ($sub) {
    $cmd =& $obj['conn']->createCommand();
    $cmd->cmdText = $file;
    foreach($params as $param) {
        $cmd->addParameter($param, $_POST[$param]);
    }
    $reader =& $cmd->executeReader();
}
echo "<pre>";
if ($_POST['__']) {
    echo $obj['conn']->queries[0]['text'];
}
echo "</pre>";
?>
