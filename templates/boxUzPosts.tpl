<ul class="sidebarItemList">
    {foreach from=$boxUserList item=$boxUser}
        <li class="box32">
            <a href="{link controller='User' object=$boxUser['user']}{/link}" aria-hidden="true">{@$boxUser['user']->getAvatar()->getImageTag(32)}</a>

            <div class="sidebarItemTitle">
                <h3>{user object=$boxUser['user']}</h3>
                <small>{lang}wcf.user.uzboxPosts.posts{/lang}</small>
            </div>
        </li>
    {/foreach}

    {if $lasts|count}
        <li>{lang}wcf.user.uzboxPosts.last{/lang}</li>

        {foreach from=$lasts item=$boxUser}
            <li class="box32">
                <a href="{link controller='User' object=$boxUser['user']}{/link}" aria-hidden="true">{@$boxUser['user']->getAvatar()->getImageTag(32)}</a>

                <div class="sidebarItemTitle">
                    <h3>{user object=$boxUser['user']}</h3>
                    <small>{lang}wcf.user.uzboxPosts.posts{/lang}</small>
                </div>
            </li>
        {/foreach}
    {/if}
</ul>
