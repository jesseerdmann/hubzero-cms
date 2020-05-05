<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// No direct access.
defined('_HZEXEC_') or die();

$notice_homes     = $this->homes == 2 || $this->homes == 1 && ($this->language_filter || $this->switchers != 0);
$notice_disabled  = !$this->language_filter	&& ($this->homes > 1 || $this->switchers != 0);
$notice_switchers = !$this->switchers && ($this->homes > 1 || $this->language_filter);
?>
<div class="mod-multilangstatus">
	<?php if (!$this->language_filter && $this->switchers == 0) : ?>
		<?php if ($this->homes == 1) : ?>
			<p><?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_NONE'); ?></p>
		<?php else: ?>
			<p><?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_USELESS_HOMES'); ?></p>
		<?php endif; ?>
	<?php else: ?>
	<table class="adminlist">
		<tbody>
		<?php if ($notice_homes) : ?>
			<tr>
				<td>
					<span title="<?php echo Lang::txt('WARNING'); ?>">
						<?php echo Html::asset('icon', 'warning-sign'); ?>
					</span>
				</td>
				<td>
					<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_HOMES_MISSING'); ?>
				</td>
			</tr>
		<?php endif; ?>
		<?php if ($notice_disabled) : ?>
			<tr>
				<td>
					<span title="<?php echo Lang::txt('WARNING'); ?>">
						<?php echo Html::asset('icon', 'warning-sign'); ?>
					</span>
				</td>
				<td>
					<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_LANGUAGEFILTER_DISABLED'); ?>
				</td>
			</tr>
		<?php endif; ?>
		<?php if ($notice_switchers) : ?>
			<tr>
				<td>
					<span title="<?php echo Lang::txt('WARNING'); ?>">
						<?php echo Html::asset('icon', 'warning-sign'); ?>
					</span>
				</td>
				<td>
					<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_LANGSWITCHER_UNPUBLISHED'); ?>
				</td>
			</tr>
		<?php endif; ?>
		<?php foreach ($this->contentlangs as $contentlang) : ?>
			<?php if (array_key_exists($contentlang->lang_code, $this->homepages) && (!array_key_exists($contentlang->lang_code, $this->site_langs) || !$contentlang->published)) : ?>
				<tr>
					<td>
						<span title="<?php echo Lang::txt('WARNING'); ?>">
							<?php echo Html::asset('icon', 'warning-sign'); ?>
						</span>
					</td>
					<td>
						<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_ERROR_CONTENT_LANGUAGE', $contentlang->lang_code); ?>
					</td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</tbody>
	</table>
	<table class="adminlist">
		<thead>
			<tr>
				<th>
					<?php echo Lang::txt('JDETAILS'); ?>
				</th>
				<th>
					<?php echo Lang::txt('JSTATUS'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th scope="row">
					<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_LANGUAGEFILTER'); ?>
				</th>
				<td class="center">
					<?php if ($this->language_filter) : ?>
						<?php echo Lang::txt('JENABLED'); ?>
					<?php else : ?>
						<?php echo Lang::txt('JDISABLED'); ?>
					<?php endif; ?>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_LANGSWITCHER_PUBLISHED'); ?>
				</th>
				<td class="center">
					<?php if ($this->switchers != 0) : ?>
						<?php echo $this->switchers; ?>
					<?php else : ?>
						<?php echo Lang::txt('JNONE'); ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php if ($this->homes > 1) : ?>
						<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_HOMES_PUBLISHED_INCLUDING_ALL'); ?>
					<?php else : ?>
						<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_HOMES_PUBLISHED'); ?>
					<?php endif; ?>
				</th>
				<td class="center">
					<?php if ($this->homes > 1) : ?>
						<?php echo $this->homes; ?>
					<?php else : ?>
						<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_HOMES_PUBLISHED_ALL'); ?>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="adminlist">
		<thead>
			<tr>
				<th>
					<?php echo Lang::txt('JGRID_HEADING_LANGUAGE'); ?>
				</th>
				<th>
					<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_SITE_LANG_PUBLISHED'); ?>
				</th>
				<th>
					<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_CONTENT_LANGUAGE_PUBLISHED'); ?>
				</th>
				<th>
					<?php echo Lang::txt('COM_LANGUAGES_MULTILANGSTATUS_HOMES_PUBLISHED'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->statuses as $status) : ?>
				<?php if ($status->element) : ?>
					<tr>
						<td>
							<?php echo $status->element; ?>
						</td>
				<?php endif; ?>
				<?php if ($status->element) : // Published Site languages ?>
						<td class="center">
							<span title="<?php echo Lang::txt('JON'); ?>">
								<?php echo Html::asset('icon', 'ok-sign'); ?>
							</span>
						</td>
				<?php else : ?>
						<td class="center">
							<?php echo Lang::txt('JNO'); ?>
						</td>
				<?php endif; ?>
				<?php if ($status->lang_code && $status->published) : // Published Content languages ?>
						<td class="center">
							<span title="<?php echo Lang::txt('JON'); ?>">
								<?php echo Html::asset('icon', 'ok-sign'); ?>
							</span>
						</td>
				<?php else : ?>
						<td class="center">
							<span title="<?php echo Lang::txt('JON'); ?>">
								<?php echo Html::asset('icon', 'warning-sign'); ?>
							</span>
						</td>
				<?php endif; ?>
				<?php if ($status->home_language) : // Published Home pages ?>
						<td class="center">
							<span title="<?php echo Lang::txt('JON'); ?>">
								<?php echo Html::asset('icon', 'ok-sign'); ?>
							</span>
						</td>
				<?php else : ?>
						<td class="center">
							<span title="<?php echo Lang::txt('WARNING'); ?>">
								<?php echo Html::asset('icon', 'warning-sign'); ?>
							</span>
						</td>
				<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			<?php foreach ($this->contentlangs as $contentlang) : ?>
				<?php if (!array_key_exists($contentlang->lang_code, $this->site_langs)) : ?>
					<tr>
						<td>
							<?php echo $contentlang->lang_code; ?>
						</td>
						<td class="center">
							<span title="<?php echo Lang::txt('NOTICE'); ?>">
								<?php echo Html::asset('icon', 'exclamation-sign'); ?>
							</span>
						</td>
						<td class="center">
							<?php if ($contentlang->published) : ?>
								<span title="<?php echo Lang::txt('JON'); ?>">
									<?php echo Html::asset('icon', 'ok-sign'); ?>
								</span>
							<?php elseif (!$contentlang->published && array_key_exists($contentlang->lang_code, $this->homepages)) : ?>
								<span title="<?php echo Lang::txt('WARNING'); ?>">
									<?php echo Html::asset('icon', 'warning-sign'); ?>
								</span>
							<?php elseif (!$contentlang->published) : ?>
								<span title="<?php echo Lang::txt('NOTICE'); ?>">
									<?php echo Html::asset('icon', 'exclamation-sign'); ?>
								</span>
							<?php endif; ?>
						</td>
						<td class="center">
							<?php if (!array_key_exists($contentlang->lang_code, $this->homepages)) : ?>
								<span title="<?php echo Lang::txt('NOTICE'); ?>">
									<?php echo Html::asset('icon', 'exclamation-sign'); ?>
								</span>
							<?php else : ?>
								<span title="<?php echo Lang::txt('JON'); ?>">
									<?php echo Html::asset('icon', 'ok-sign'); ?>
								</span>
							<?php endif; ?>
						</td>
				<?php endif; ?>
			<?php endforeach; ?>
			</tr>
		</tbody>
	</table>
	<?php endif; ?>
</div>
