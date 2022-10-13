export default {
    showTitleText(val) {
        let showTitle = null;
        switch (val) {
            case "invoice-sync":
                showTitle = "Smart Soft Invoice Sync";
                break;
            case "owner-sms-send":
                showTitle = "Sent Owner Daily Sales Message";
                break;
            case "product-sync":
                showTitle = "Smart Soft Product Sync";
                break;
            default:
                showTitle = val;
        }
        return showTitle;
    }
}