https://danthesalmon.com/

#Known Issues
Navbar: fixed-top - The navbar doesn't toggle the fixed-top class properly if the height of the viewport is changed after the page is loaded. The problem doesn't persist if the page is reloaded and the height is re-calculated (var origOffsetY = menu.offset().top;)
Possible Fix: Create event fired when viewport height is changed to re-calculate origOffsetY.