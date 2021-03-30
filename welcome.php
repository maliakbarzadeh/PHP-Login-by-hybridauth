<?php
/**
 * Build a simple HTML page with multiple providers, opening provider authentication in a pop-up.
 */

require 'vendor/autoload.php';
require 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Welcome</title>
	<meta charset="UTF-8">
</head>
<body>
	<span>
	</span>
	<?php if ($adapters) : ?>
		<h1>You are logged in:</h1>
		<ul>
			<?php foreach ($adapters as $name => $adapter) : ?>
				<li>
					<?php if (isset($adapter->getUserProfile()->photoURL)):?>
						<img src="<?php print $adapter->getUserProfile()->photoURL; ?>" width="100" height="100" >
					<?php endif; ?>
				</li>
				<li>
					<strong><?php print $adapter->getUserProfile()->displayName; ?></strong> from
					<i><?php print $name; ?></i>
					<span>(<a href="<?php print $config['callback'] . "?logout={$name}"; ?>">Log Out</a>)</span>
				</li>
			<?php endforeach;?>
		</ul>
	<?php endif; ?>
</body>