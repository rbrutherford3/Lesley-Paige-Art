<?php

require_once 'paths.php';

function footerHTML() {
	echo '
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="' . BOOTSTRAP_JS_HTML . '"></script>
	</body>
</html>';
}

?>