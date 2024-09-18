import React from "react";
import { useLocation } from "react-router-dom";
import WCTPTab from "./tabs";

const TopPickBySale = () => {
  const useQuery = () => new URLSearchParams(useLocation().hash);

  const TopPickBySale = () => {
    const $ = jQuery;
    const menuRoot = $("#toplevel_page_" + "wc-top-pick-by-sale-setting");

    const currentUrl = window.location.href;
    const currentPath = currentUrl.substr(currentUrl.indexOf("admin.php"));

    menuRoot.on("click", "a", function () {
      const self = $(this);
      $("ul.wp-submenu li", menuRoot).removeClass("current");
      if (self.hasClass("wp-has-submenu")) {
        $("li.wp-first-item", menuRoot).addClass("current");
      } else {
        self.parents("li").addClass("current");
      }
    });

    $("ul.wp-submenu a", menuRoot).each(function (index, el) {
      if ($(el).attr("href") === currentPath) {
        $(el).parent().addClass("current");
      } else {
        $(el).parent().removeClass("current");
        if (
          $(el).parent().hasClass("wp-first-item") &&
          currentPath === "admin.php?page=wc-top-pick-by-sale-setting"
        ) {
          $(el).parent().addClass("current");
        }
      }
    });

    const location = useQuery();

    if (location.get("tab") && location.get("tab") === "settings") {
      return (
        <WCTPTab
          model="top-picks-settings"
          query_name={location.get("tab")}
          subtab={location.get("subtab")}
          funtion_name={TopPickBySale}
        />
      );
    } else {
      return (
        <WCTPTab
          model="top-picks-settings"
          query_name="settings"
          subtab="general"
          funtion_name={TopPickBySale}
        />
      );
    }
  };

  return (
      TopPickBySale()
  );
};

export default TopPickBySale;