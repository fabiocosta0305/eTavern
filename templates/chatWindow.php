<div id="tableWindow">
  <div id="chatWindows">
    <div id="chatRoom">
      <div id="onChat">
        <div class="chatHeaders">
          Adventure
        </div>
        <div id="onChatData">
        </div>
      </div>
      <div id="offChat">
        <div class="chatHeaders">
          Off-adventure
        </div>
        <div id="offChatData">
        </div>
      </div>
    </div>
    <div class="chatBar">
      <form id="chatForm">
        <input id="lastOffTimestamp" name="lastOffTimestamp" type="hidden" value="1"/>
        <input id="lastOnTimestamp" name="lastOnTimestamp" type="hidden" value="0"/>
        <input style="width:80%;" name="chatEntry" id="chatEntry" placeholder="Type what you want..." type="text"/>
        <button type="submit" class="btn">Speak</button>
        <a href class="charLink" onClick="return popitup('/help/chatCommands.html');">(Chat Commands)</a>
      </form>
    </div>
  </div>
  <div id="chatPeople">
    <div class="chatHeaders">
      Players on ths table
    </div>
    <div id="people"></div>
  </div>
</div>
