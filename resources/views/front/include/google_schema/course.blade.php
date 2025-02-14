
<!-- https://developers.google.com/search/docs/appearance/structured-data/search-gallery -->

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "{{ $google_info['name'] }}",
  "description": "{{ $google_info['description'] }}",
  "provider": {
    "@type": "Organization",
    "name": "{{ $google_info['author'] }}",
    "sameAs": "{{ url()->current() }}"
  }
}
</script>
