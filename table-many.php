<?php

require_once 'paths.php';

echo '
										<div class="arttable">
											<div class="artrow">
												<div class="artcell">
													<a href = "' . ROOT_HTML . 'art.php?id=', $ids[$i], '">
														<img class = "art" src = "', THUMBNAILS_HTML, $filenames[$i], '.', EXT, '" alt = "', $names[$i], '">
													</a>
												</div>
											</div>
											<div class="labelrow">
												<div class="labelcell">
													<a href = "' . ROOT_HTML . 'art.php?id=', $ids[$i], '">
														<h1>', $names[$i], '</h1>
													</a>
												</div>
											</div>
										</div>';
?>