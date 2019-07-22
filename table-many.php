<?php
	echo	'							<div class="arttable">
											<div class="artrow">
												<div class="artcell">
													<a href = "art.php?id=', $ids[$i], '">
														<img class = "art" src = "img/art/', $filenames[$i], '.gif" alt = "', $names[$i], '">
													</a>
												</div>
											</div>
											<div class="labelrow">
												<div class="labelcell">
													<a href = "art.php?id=', $ids[$i], '">
														<img class = "artlabel" src = "img/art_labels/', $filenames[$i], '.png" alt = "', $names[$i], '">
													</a>
												</div>
											</div>
										</div>';
?>