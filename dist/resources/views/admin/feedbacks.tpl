{extends file="index.tpl"}

{block name="content-wrapper"}
<table class="feedback-list table table-condensed">
{foreach from=$records item=record}
    <tr>
        <td>
            <div class="info">
                ID: #{$record.id} | <span class="label">{$record.cdate}</span>
            </div>
            <div class="from-to">
                <div class="from">{$record.sender|htmlspecialchars}</div> 

                <div class="to">
                    <a href="mailto:{$record.recipient|htmlspecialchars}">{$record.recipient|htmlspecialchars}</a> 
                    <small>[IP: <a href="http://www.infobyip.com/ip-{$record.ip}.html" target="_blank">{$record.ip}</a>]</small>
                </div>
            </div>
            <div class="subject">
                <label>Тема:</label> <span>{$record.title|htmlspecialchars}</span>
            </div>
            <div class="body">
                {$record.body}
            </div>

            <div class="comment">
                <p class="muted">{$record.comment|htmlspecialchars}</p>
                {if $record.additional != ''}
                    <p class="text-info">{$record.additional|htmlspecialchars}</p>
                {/if}
            </div>

            <div class="status-box">
                Статус: <span class="status">{$record.status}</span>
            </div>
            <div class="controls">
                <form class="form-inline">
                    <input type="hidden" name="id" value="{$record.id}">
                    <button class="btn btn-mini btn-success">Reply</button>
                    <button class="btn btn-mini btn-danger">Delete</button> 

                    <div class="btn-group">
                        <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                            Action
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="fb-mark-as-seen" data-id="{$record.id}">Mark as Seen</a></li>
                            <li><a href="#" class="fb-mark-as-seen" data-id="{$record.id}">Mark as New</a></li>
                            <li><a href="#" class="fb-mark-as-seen" data-id="{$record.id}">Mark as Done</a></li>
                        </ul>
                    </div>
                </form>
            </div>
        </td>
    </tr>
{/foreach}
</table>
{/block}