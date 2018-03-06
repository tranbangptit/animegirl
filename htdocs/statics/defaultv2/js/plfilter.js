jQuery(document).ready(function() {
jQuery('input.btn.btn-info').click(function() {
        
            var arrange = jQuery('#filter-sort').val();
            var eptype = jQuery('#filter-eptype').val();        
            var country = jQuery('#filter-country').val();
			var category = jQuery('#filter-category').val();
            var year = jQuery('#filter-year').val();

            var submitPath = '';

            if (eptype != '') {
                switch (eptype) {
                    case 'phim-chieu-rap':
                        submitPath += 'phim-chieu-rap/';
                        break;
                    case 'phim-le':
                        submitPath += 'phim-le/';
                        break;
                    case 'phim-bo':
                        submitPath += 'phim-bo/';
                        break;
					case 'phim-hot':
                        submitPath += 'phim-hot/';
                        break;
					case 'phim-18':
                        submitPath += 'phim-18/';
                        break;
                    case 'phim-moi':
                        submitPath += 'phim-moi/';
                        break;
      						
                }
            }
            if (category != '') {
                if (submitPath == '')
                    submitPath = 'the-loai/';
                submitPath += jQuery('#filter-category').val() + '/';
            }

            if (country != '') {
                if (submitPath == '') {
                    submitPath = 'quoc-gia/' + country + '/';
                } else {
                    submitPath += country + '/';
                }
            }

            if (year != '') {
                if (submitPath == '')
                    submitPath += 'phim-' + year + '/';
                else
                    submitPath += year + '/';
            }
            if (arrange != '' && arrange!='update') {
                switch (arrange) {
                   
                    case 'new':
                        submitPath += '?rel=new';
                        break;
                    case 'popular':
                        submitPath += '?rel=popular';
                        break;
					case 'year':
                        submitPath += '?rel=year';
                        break;
					case 'name':
                        submitPath += '?rel=name';
                        break;
      						
                }
            }
			var linkdirect = MAIN_URL + '/' + submitPath;
            if (linkdirect != window.location)
                        window.history.pushState({
                            path: linkdirect
                        }, '', linkdirect);
            window.location.replace(linkdirect);
            return false;
        
    });
});