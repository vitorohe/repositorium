<?php $this->viewVars['title_for_layout'] = 'Repositorium'; ?>

<div class="yui-g">
    <div class="yui-g first">
        <div class="yui-u first">
        	<div class="padded">
				<strong class="mini-header">Your created Repositories</strong>
				<?php if(count($your_repos) > 0): ?>
				<ul>
					<?php foreach($your_repos as $r) { ?>
					<li><?php echo $this->Repo->link($r['Repository']['name'], $r['Repository']['internal_name']); ?></li>
					<?php } ?>
				</ul>
				<?php else: ?>
				<div style="text-align: center; font-style: italic; color: #777;"><span>There aren't repositories here</span></div>
				<?php endif; ?>
			</div> 
	    </div>
        <div class="yui-u">
        	<div class="padded">
				<strong class="mini-header">Your criterias</strong>
				<?php if(count($your_criterias) > 0): ?>
				<ul>
					<?php foreach($your_criterias as $cr) { ?>
					<li><?php echo $this->Html->link(Sanitize::html($cr['Criteria']['name']), array('controller' => 'admin_criterias', 'action' => 'edit', $cr['Criteria']['id']));?></li>
					<?php } ?>
				</ul>
				<?php else: ?>
				<div style="text-align: center; font-style: italic; color: #777;"><span>There aren't criterias here</span></div>
				<?php endif; ?>
			</div>			
	    </div>
    </div>
    <div class="yui-g">
        <div class="yui-u first">
        	<div class="padded">
				<strong class="mini-header">Repositories you are watching</strong>
				<?php if(count($watching_repos) > 0): ?>
				<ul>
					<?php foreach($watching_repos as $r) { ?>
					<li><?php echo $this->Repo->link($r['Repository']['name'], $r['Repository']['internal_name']); ?></li>
					<?php } ?>
				</ul>
				<?php else: ?>
				<div style="text-align: center; font-style: italic; color: #777;"><span>There aren't repositories here</span></div>
				<?php endif; ?>
                
			</div>
	    </div>
        <div class="yui-u">
	        <div class="padded">
				<strong class="mini-header">Newest repositories</strong>
				<?php if(count($latest_repos) > 0): ?>
				<ul>
					<?php foreach($latest_repos as $r) { ?>
					<li><?php echo $this->Repo->link($r['Repository']['name'], $r['Repository']['internal_name']); ?></li>
					<?php } ?>
				</ul>
				<?php else: ?>
				<div style="text-align: center; font-style: italic; color: #777;"><span>There aren't repositories here</span></div>
				<?php endif; ?>
			</div>
	    </div>
    </div>
</div>