function unsecuredCopyToClipboard(text) {
  const textArea = document.createElement("textarea")
  textArea.value = text
  document.body.appendChild(textArea)
  textArea.focus({ preventScroll: true })
  textArea.select()
  try {
    document.execCommand("copy")
  } catch (err) {
    console.error("Unable to copy to clipboard", err)
  }
  document.body.removeChild(textArea)
}

jQuery(function ($) {
  let awaitingVariant = null

  const selectLayout = ($layout) => {
    const layout = $layout.data("layout")
    const label = $layout.text()

    // clear previous preview
    $(".layout-picker-previews img.selected").removeClass("selected")
    // select new preview
    $(".layout-picker-previews img[data-layout='" + layout + "']").addClass(
      "selected"
    )

    // clear previous selected
    $(".layout-picker-item.selected").removeClass("selected")
    // select new selected
    $(".layout-picker-item[data-layout='" + layout + "']").addClass("selected")

    // update the add button text
    $(".boo-acf-pb-layout-picker-modal .button[data-add-layout]").text(
      "Add " + label + " layout"
    )
  }

  $(".layout-picker-item").on("click", function (e) {
    e.preventDefault()
    selectLayout($(this))
  })

  // preview on hover
  $(".layout-picker-item")
    .on("mouseenter", function () {
      const layout = $(this).data("layout")

      // if this isn't the selected img add hide-while-previewing to selected
      if (!$(this).hasClass("selected")) {
        $(".layout-picker-previews img.selected").addClass(
          "hide-while-previewing"
        )
        $(".preview-text").show()
      }

      $(".layout-picker-previews img.previewing").removeClass("previewing")
      $(".layout-picker-previews img[data-layout='" + layout + "']").addClass(
        "previewing"
      )
    })
    .on("mouseleave", function () {
      $(".layout-picker-previews img.previewing").removeClass("previewing")

      // remove hide-while-previewing class
      $(".layout-picker-previews img").removeClass("hide-while-previewing")
      $(".preview-text").hide()
    })

  $(".boo-acf-pb-layout-picker-modal .button[data-add-layout]").on(
    "click",
    function (e) {
      e.preventDefault()
      const layout = $(".layout-picker-item.selected").data("layout")
      addLayout(layout)
      $(".boo-acf-pb-layout-picker-container").hide()
    }
  )

  $("[data-layout-picker-open]").on("click", function (e) {
    e.preventDefault()
    $(".boo-acf-pb-layout-picker-container").css("display", "flex")
    // focus search
    $("[data-layout-picker-search]").focus()
  })

  const filterLayouts = (search) => {
    // hide all layouts not matching search
    $(".layout-picker-item").each(function () {
      const label = $(this).text().toLowerCase()
      if (label.includes(search)) {
        $(this).show()
      } else {
        $(this).hide()
      }
    })
    // select the first visible layout
    const $selected = $(".layout-picker-item:visible").first()
    selectLayout($selected)
  }

  const showAllLayouts = () => {
    $(".layout-picker-item").show()
    // select the first layout
    const $selected = $(".layout-picker-item").first()
    selectLayout($selected)
    hideSearchTip()
  }

  $("[data-layout-picker-search]").on("input", function (event) {
    // prevent default form submission
    event.preventDefault()

    const search = $(this).val().toLowerCase()
    if (search) {
      filterLayouts(search)
      showSearchTip()
    } else {
      showAllLayouts()
    }
  })

  const showSearchTip = () => {
    $(".boo-acf-pb-layout-picker-modal").addClass("--show-tip")
  }

  const hideSearchTip = () => {
    $(".boo-acf-pb-layout-picker-modal").removeClass("--show-tip")
  }

  // if up and down arrow keys are pressed in the search input
  // then select the next or previous layout
  $("[data-layout-picker-search]").on("keydown", function (e) {
    const key = e.which
    if (key === 38 || key === 40) {
      e.preventDefault()
      const $selected = $(".layout-picker-item.selected")
      const $visible = $(".layout-picker-item:visible")
      const index = $visible.index($selected)
      let nextIndex = index + 1
      if (key === 38) {
        nextIndex = index - 1
      }
      if (nextIndex < 0) {
        return
      }
      if (nextIndex >= $visible.length) {
        return
      }
      const $next = $visible.eq(nextIndex)
      selectLayout($next)
    }
  })

  // if escape key is pressed close the modal
  $(document).on("keydown", function (e) {
    if (e.which === 27) {
      $("[data-layout-picker-search]").val("")
      showAllLayouts()
      $(".boo-acf-pb-layout-picker-container").hide()
      // clear search
    }
  })

  // if enter key is pressed in search add the selected layout
  $("[data-layout-picker-search]").on("keydown", function (e) {
    if (e.which === 13) {
      e.preventDefault()

      const $selected = $(".layout-picker-item.selected")
      if ($selected.length) {
        addLayout($selected.data("layout"))

        // clear search
        $(this).val("")
        showAllLayouts()

        // was ctrl key also pressed?
        if (!e.ctrlKey) {
          // no - hide modal
          $(".boo-acf-pb-layout-picker-container").hide()
        }
        // yes - continue searching
      }
    }
  })

  // if modal container is clicked close the modal
  $(".boo-acf-pb-layout-picker-container").on("click", function (e) {
    if (e.target === this) {
      $(this).hide()
    }
  })

  const appendImagePreview = function ($el) {
    const layout = $el.data("layout")
    const imgSrc = boo_acf_pb.plugin_url + "/layouts/" + layout + ".jpg"
    const img = $("<img />", {
      src: imgSrc,
      class: "layout-image",
    })

    const imgPreview = $("<div />", {
      class: "boo-acf-pb-layout-image-preview",
    })
    imgPreview.on("click", function (e) {
      e.preventDefault()
      $el.removeClass("boo-acf-pb-layout-image-preview-active")
    })
    imgPreview.append(img)

    const button = $("<button />", {
      class: "boo-acf-pb-layout-image-preview-button",
    })

    const icon = $("<span />", {
      class: "dashicons dashicons-format-image",
    })

    button.append(icon)
    button.on("click", function (e) {
      e.stopPropagation()
      e.preventDefault()
      $el.addClass("boo-acf-pb-layout-image-preview-active")
    })

    $el.find(".acf-fc-layout-handle").append(button)
    $el.find(".acf-fields").append(imgPreview)
  }

  const appendCodeMenu = function ($el) {
    const php = []

    const $fields = $el.find(".acf-field")
    $fields.each(function () {
      const $field = $(this)
      const name = $field.data("name")
      if (!name) {
        return
      }
      const type = $field.data("type")
      const line = `$${name} = get_sub_field('${name}'); // ${type}`
      php.push(line)
    })

    const icon = $("<span />", {
      class: "dashicons dashicons-editor-code",
    })

    const button = $("<button />", {
      class: "boo-acf-pb-layout-code-button",
    })

    button.append(icon)
    button.on("click", function (e) {
      e.stopPropagation()
      e.preventDefault()
      const code = php.join("\n")
      unsecuredCopyToClipboard(code)
    })

    $el.find(".acf-fc-layout-handle").append(button)
  }

  acf.addAction("load", function () {
    const fields = acf.findField()
    fields.each(function () {
      // if name is page_builder
      const $this = $(this)
      const name = $this.data("name")
      if (name === "page_builder") {
        const $layouts = $this.find(".layout:not(.acf-clone)")
        $layouts.each(function () {
          appendImagePreview($(this))
          appendCodeMenu($(this))
        })
      }
    })
  })

  acf.addAction("append", function ($el) {
    // console.log({ $el });
    if ($el.hasClass("layout")) {
      appendImagePreview($el)
      appendCodeMenu($el)

      // if we are waiting for a variant then select it
      if (awaitingVariant) {
        console.log({ awaitingVariant })
        const $field = $el.find("[data-name='layout_variant']")
        if ($field.length) {
          $field.find("select").val(awaitingVariant)
          awaitingVariant = null
        }
      }
    }
  })

  const addLayout = (layout) => {
    $(".acf-button[data-name='add-layout']").trigger("click")
    $(".acf-fc-popup a[data-layout='" + layout + "']").trigger("click")
  }

  $(".flexible-content-test").on("click", function (e) {
    e.preventDefault()
    const layout = $(this).data("layout")
    // console.log({ layout });
    addLayout(layout)

    // $(".acf-button[data-name='add-layout']").trigger("click");
    // $(".acf-fc-popup a[data-layout='" + layout + "']").trigger("click");
  })

  $("[data-preset]").on("click", function (e) {
    e.preventDefault()
    e.stopPropagation()
    const preset = $(this).data("preset")
    const layouts = preset.split(",")
    layouts.forEach((layout) => {
      let variant = layout.split("--")
      console.log({ variant })
      if (variant.length > 1) {
        layout = variant[0]
        variant = variant[1]
        // if we have a variant we'll select it after the layout is added
        awaitingVariant = variant
      }
      addLayout(layout)
    })
  })

  $(".boo-acf-pb-layout-picker-buttons button").on("mouseenter", function () {
    const layout = $(this).data("layout")
    $(".boo-acf-pb-layout-picker-preview img").hide()
    const $img = $(
      ".boo-acf-pb-layout-picker-preview img[data-layout='" + layout + "']"
    ).show()
  })
})
