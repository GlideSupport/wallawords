wp.blocks.registerBlockStyle("core/button", {
 label: "Site Arrow Btn",
 name: "site-arrow-btn",
});
wp.blocks.registerBlockStyle("core/button", {
 label: "Site Secondary Btn",
 name: "site-secondary-btn",
});
wp.blocks.registerBlockStyle("core/button", {
 label: "Site Text Btn",
 name: "site-text-btnnpm",
});
wp.blocks.registerBlockStyle("core/button", {
 label: "Site Btn Small",
 name: "site-btn-small",
});
wp.domReady(() => {
 wp.blocks.unregisterBlockStyle("core/button", "outline"); 
 wp.blocks.unregisterBlockStyle("core/button", "fill");
});
 