
<!-- https://developers.google.com/search/docs/appearance/structured-data/search-gallery -->

<script type="application/ld+json">
{
   "@context": "https://schema.org",
   "@type": ["VideoObject", "LearningResource"],
   "name": "{{ $google_info['name'] }}",
   "description": "{{ $google_info['description'] }}",
   "learningResourceType": "Concept Overview",
   "educationalLevel": "Islam Academy",
   "contentUrl": "{{ $google_info['video'] }}",
   "thumbnailUrl": [
     "{{ $google_info['image'] }}"
   ],
   "uploadDate": "{{ $google_info['created_at'] }}"
}
</script>
