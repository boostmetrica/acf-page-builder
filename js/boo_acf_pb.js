jQuery(function ($) {
  const appendImagPreview = function ($el) {
    console.log({ $el });
    const layout = $el.data("layout");
    const imgSrc = boo_acf_pb.plugin_url + "/layouts/" + layout + ".jpg";
    const img = $("<img />", {
      src: imgSrc,
      class: "layout-image",
    });

    const imgPreview = $("<div />", {
      class: "boo-acf-pb-layout-image-preview",
    });
    imgPreview.on("click", function (e) {
      e.preventDefault();
      $el.removeClass("boo-acf-pb-layout-image-preview-active");
    });
    imgPreview.append(img);

    const button = $("<button />", {
      class: "boo-acf-pb-layout-image-preview-button",
    });

    const icon = $("<span />", {
      class: "dashicons dashicons-format-image",
    });

    button.append(icon);
    button.on("click", function (e) {
      e.stopPropagation();
      e.preventDefault();
      $el.addClass("boo-acf-pb-layout-image-preview-active");
    });

    $el.find(".acf-fc-layout-handle").append(button);
    $el.find(".acf-fields").append(imgPreview);
  };

  acf.addAction("load", function () {
    console.log({ acf });
    const fields = acf.findField();
    fields.each(function () {
      // if name is page_builder
      const $this = $(this);
      const name = $this.data("name");
      if (name === "page_builder") {
        const $layouts = $this.find(".layout:not(.acf-clone)");
        $layouts.each(function () {
          appendImagPreview($(this));
        });
      }
    });
  });

  acf.addAction("append", function ($el) {
    console.log({ $el });
    if ($el.hasClass("layout")) {
      appendImagPreview($el);
    }
  });

  $(".flexible-content-test").on("click", function (e) {
    e.preventDefault();
    const layout = $(this).data("layout");
    // console.log({ layout });

    $(".acf-button[data-name='add-layout']").trigger("click");
    $(".acf-fc-popup a[data-layout='" + layout + "']").trigger("click");
  });

  $(".boo-acf-pb-layout-picker-buttons button").on("mouseenter", function () {
    const layout = $(this).data("layout");
    $(".boo-acf-pb-layout-picker-preview img").hide();
    const $img = $(
      ".boo-acf-pb-layout-picker-preview img[data-layout='" + layout + "']"
    ).show();
  });
});
