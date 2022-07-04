 <div class="category">
     <a class="category-link" href="{{ route('seo.url', ['slug' => $hero->url]) }}">
         <div class="category-img-wrap">
             <img src="{{ $hero->getUrlImage() }}"
                  class="demilazyload"
                  data-srcset="{{ $hero->getUrlImage() }} 100w"
                  srcset="/images/watermark_300x300.png 100w"
                  sizes="100vw"
                  alt="{{ $hero->name }}">
         </div>
         <div class="category-name">
             <div class="category-text">{{ $hero->name}}</div>
         </div>
     </a>
 </div>