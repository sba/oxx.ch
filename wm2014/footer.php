											</td>
											<td class="right"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
										</tr>
										<tr>
											<td class="bl"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
											<td class="bottom"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
											<td class="br"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="52">
						<table width="100%" height="50" border="0" cellpadding="0" cellspacing="0" class="footer">
							<tr>
								<td class="right" height="50" style="padding-left: 20px; padding-right: 20px; padding-top: 10px;">
									<?php
									if(!$cfg_einsatz['on'] || !$cfg_einsatz['hide_footer']){
										?>
										<?=$cfg_application['copyright']?>&nbsp;&nbsp;|&nbsp;&nbsp;
										<?php
									}
									?>
									<a href="#top"><?=$lg_top?></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>