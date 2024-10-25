function unsecuredCopyToClipboard(text) {
  const textArea = document.createElement("textarea");
  textArea.value = text;
  document.body.appendChild(textArea);
  textArea.focus({ preventScroll: true });
  textArea.select();
  try {
    document.execCommand("copy");
  } catch (err) {
    console.error("Unable to copy to clipboard", err);
  }
  document.body.removeChild(textArea);
}

jQuery(function ($) {
  let awaitingVariant = null;

  const appendImagePreview = function ($el) {
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

  const appendCodeMenu = function ($el) {
    console.log({ $el });
    const php = [];

    const $fields = $el.find(".acf-field");
    $fields.each(function () {
      const $field = $(this);
      const name = $field.data("name");
      if (!name) {
        return;
      }
      const type = $field.data("type");
      const line = `$${name} = get_sub_field('${name}'); // ${type}`;
      php.push(line);
    });

    const icon = $("<span />", {
      class: "dashicons dashicons-editor-code",
    });

    const button = $("<button />", {
      class: "boo-acf-pb-layout-code-button",
    });

    button.append(icon);
    button.on("click", function (e) {
      e.stopPropagation();
      e.preventDefault();
      const code = php.join("\n");
      unsecuredCopyToClipboard(code);
    });

    $el.find(".acf-fc-layout-handle").append(button);
  };

  acf.addAction("load", function () {
    const fields = acf.findField();
    fields.each(function () {
      // if name is page_builder
      const $this = $(this);
      const name = $this.data("name");
      if (name === "page_builder") {
        const $layouts = $this.find(".layout:not(.acf-clone)");
        $layouts.each(function () {
          appendImagePreview($(this));
          appendCodeMenu($(this));
        });
      }
    });
  });

  acf.addAction("append", function ($el) {
    // console.log({ $el });
    if ($el.hasClass("layout")) {
      appendImagePreview($el);
      appendCodeMenu($el);

      // if we are waiting for a variant then select it
      if (awaitingVariant) {
        console.log({ awaitingVariant });
        const $field = $el.find("[data-name='layout_variant']");
        if ($field.length) {
          $field.find("select").val(awaitingVariant);
          awaitingVariant = null;
        }
      }
    }
  });

  const addLayout = (layout) => {
    $(".acf-button[data-name='add-layout']").trigger("click");
    $(".acf-fc-popup a[data-layout='" + layout + "']").trigger("click");
  };

  $(".flexible-content-test").on("click", function (e) {
    e.preventDefault();
    const layout = $(this).data("layout");
    // console.log({ layout });
    addLayout(layout);

    // $(".acf-button[data-name='add-layout']").trigger("click");
    // $(".acf-fc-popup a[data-layout='" + layout + "']").trigger("click");
  });

  $("[data-preset]").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    const preset = $(this).data("preset");
    const layouts = preset.split(",");
    layouts.forEach((layout) => {
      let variant = layout.split("--");
      console.log({ variant });
      if (variant.length > 1) {
        layout = variant[0];
        variant = variant[1];
        // if we have a variant we'll select it after the layout is added
        awaitingVariant = variant;
      }
      addLayout(layout);
    });
  });

  $(".boo-acf-pb-layout-picker-buttons button").on("mouseenter", function () {
    const layout = $(this).data("layout");
    $(".boo-acf-pb-layout-picker-preview img").hide();
    const $img = $(
      ".boo-acf-pb-layout-picker-preview img[data-layout='" + layout + "']"
    ).show();
  });
});
