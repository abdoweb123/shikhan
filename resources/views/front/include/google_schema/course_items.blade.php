
<!-- https://developers.google.com/search/docs/appearance/structured-data/search-gallery -->

{!! $google_schema_course_items !!}

{{--
<script type="text/javascript">
    var itemsList = [];
    itemsList[0] = {
        "@type": "ListItem",
        "position": 1,
        "item": {
          "@type": "Course",
          "url":"https://www.example.com/courses#intro-to-cs",
          "name": "Introduction to Computer Science and Programming",
          "description": "This is an introductory CS course laying out the basics.",
          "provider": {
            "@type": "Organization",
            "name": "University of Technology - Example",
            "sameAs": "https://www.example.com"
         }
        }
    };
    itemsList[1] = {
        "@type": "ListItem",
        "position": 2,
        "item": {
          "@type": "Course",
          "url":"https://www.example.com/courses#intro-to-cs",
          "name": "b",
          "description": "b",
          "provider": {
            "@type": "Organization",
            "name": "University of Technology - Example",
            "sameAs": "https://www.example.com"
         }
        }
    };

    var el = document.createElement('script');
    el.type = 'application/ld+json';
    el.text = JSON.stringify({
          "@context": "https://schema.org",
          "@type": "ItemList",
          "itemListElement": itemsList
    });

    document.body.appendChild(el);
</script>
--}}
