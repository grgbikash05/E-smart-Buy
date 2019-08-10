import bs4
from urllib.request import urlopen as uReq
from bs4 import BeautifulSoup as soup
import time
from flask import Flask, jsonify, request
app = Flask(__name__)

@app.route('/<string:search_query>', methods=['GET'])
def test(search_query):
    results = webscrape(search_query)
    return jsonify(results)

def price_filter(price_of_product):
    temp = ''
    found = False
    for c in price_of_product:
        try:
            int(c)
            temp+=c
            found=True
        except:
            if(c!=',' and found==True):
                break
    return temp

def webscrape(search_query):

    product = []

    # this is for gajabko
    try:
        uClient = uReq("http://gajabko.com/?s=" + search_query)

        page_html = uClient.read()

        uClient.close()

        page_soup = soup(page_html, "html.parser")

        containers = page_soup.find_all("li", {"class" : "product"})

        if len(containers) <= 15:
            for container in containers:
                title_of_product = container.find("a", {"class" : "woocommerce-LoopProduct-link"}).h3.text
                price_of_product = container.find("span", {"class" : "woocommerce-Price-amount"}).text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("a", {"class" : "woocommerce-LoopProduct-link"}).img['src']
                link_of_product = container.a['href']
                if(price_of_product == '0'):
                    print('')
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : link_of_product, "site" : "http://gajabko.com/wp-content/themes/gajabkotheme/img/logo.png"})
        else:
            i = 0
            for container in containers:
                title_of_product = container.find("a", {"class" : "woocommerce-LoopProduct-link"}).h3.text
                price_of_product = container.find("span", {"class" : "woocommerce-Price-amount"}).text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("a", {"class" : "woocommerce-LoopProduct-link"}).img['src']
                link_of_product = container.a['href']
                if(price_of_product == '0'):
                    print('')
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : link_of_product, "site" : "http://gajabko.com/wp-content/themes/gajabkotheme/img/logo.png"})
                    i = i + 1
                    if i == 15:
                        break
    except:
        pass

    # This is for muncha

    try:
        uClient = uReq("https://muncha.com/Shop/Search?merchantID=1&CategoryID=0&q=" + search_query)

        page_html = uClient.read()

        uClient.close()

        page_soup = soup(page_html, "html.parser")

        containers = page_soup.find_all("div", {"class" : "product"})

        if len(containers) <= 15:
            for container in containers:
                title_of_product = container.find("h5", {"class" : "product-caption-title-sm"}).text
                price_of_product = container.find("span", {"class" : "product-caption-price-new"}).text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("div", {"class" : "product-img-wrap"}).img['src']
                link_of_product = container.find("a", {"class" : "product-link"})['href']
                if(price_of_product == '0'):
                    print('')
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : "https://muncha.com/" + link_of_product, "site" : "https://muncha.com/assets/images/logo.gif"})
        else:
            i = 0
            for container in containers:
                title_of_product = container.find("h5", {"class" : "product-caption-title-sm"}).text
                price_of_product = container.find("span", {"class" : "product-caption-price-new"}).text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("div", {"class" : "product-img-wrap"}).img['src']
                link_of_product = container.find("a", {"class" : "product-link"})['href']
                if(price_of_product != '0'):
                    print("")
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : "https://muncha.com/" + link_of_product, "site" : "https://muncha.com/assets/images/logo.gif"})
                    i = i + 1
                    if i == 15:
                        break
    except:
        pass
        
    # this is for nepbay
    try:
        uClient = uReq("https://market.thulo.com/shopping/search?q=" + search_query)

        page_html = uClient.read()

        uClient.close()

        page_soup = soup(page_html, "html.parser")

        containers = page_soup.find_all("div", {"class" : "thirdGridActive"})

        if len(containers) <= 15:
            for container in containers:
                title_of_product = container.h4.a.text
                price_of_product = container.p.text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("div", {"class" : "PicImgGrid"}).a.img['src']
                link_of_product = container.find("div", {"class" : "PicImgGrid"}).a['href']
                if(price_of_product == '0'):
                    print("")
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : link_of_product, "site" : "https://market.thulo.com/uploads/2018082815354524400.svg"})
        else:
            i = 0
            for container in containers:
                title_of_product = container.h4.a.text
                price_of_product = container.p.text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("div", {"class" : "PicImgGrid"}).a.img['src']
                link_of_product = container.find("div", {"class" : "PicImgGrid"}).a['href']
                if(price_of_product == '0'):
                    print("")
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : link_of_product, "site" : "https://market.thulo.com/uploads/2018082815354524400.svg"})
                    i = i + 1
                    if i == 15:
                        break
    except:
        pass

    # this is for nepkart
    try:
        uClient = uReq("https://www.nepkart.com/index.php?route=product/search&search=" + search_query)

        page_html = uClient.read()

        uClient.close()

        page_soup = soup(page_html, "html.parser")

        containers = page_soup.find_all("div", {"class" : "product-block"})

        if len(containers) <= 15:
            for container in containers:
                title_of_product = container.find("h3", {"class" : "name"}).a.text
                price_of_product = container.find("span", {"class" : "special-price"}).text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("div", {"class" : "image"}).a.img['src']
                link_of_product = container.find("div", {"class" : "image"}).a['href']
                if(price_of_product == '0'):
                    print("")
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : link_of_product, "site" : "https://www.nepkart.com/image/data/nepkart-logo.png"})
        else:
            i = 0
            for container in containers:
                title_of_product = container.find("h3", {"class" : "name"}).a.text
                price_of_product = container.find("span", {"class" : "special-price"}).text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("div", {"class" : "image"}).a.img['src']
                link_of_product = container.find("div", {"class" : "image"}).a['href']
                if(price_of_product == '0'):
                    print("")
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : link_of_product, "site" : "https://www.nepkart.com/image/data/nepkart-logo.png"})
                    i = i + 1
                    if i == 15:
                        break
    except:
        pass

    # this is for olizstore
    try:
        uClient = uReq("https://www.olizstore.com/catalogsearch/result/?q=" + search_query)

        page_html = uClient.read()

        uClient.close()

        page_soup = soup(page_html, "html.parser")

        containers = page_soup.find_all("li", {"class" : "product-item"})

        if len(containers) <= 15:
            for container in containers:
                title_of_product = container.find("a", {"class" : "product-item-link"}).text.strip()
                price_of_product = container.find("span", {"class" : "price"}).text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("div", {"class" : "product-item-photo"}).a.img['src']
                link_of_product = container.find("div", {"class" : "product-item-photo"}).a['href']
                if(price_of_product == '0'):
                    print("")
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : link_of_product, "site" : "https://www.olizstore.com/pub/media/porto/sticky_logo/default/olizLogo.png"})
        else:
            i = 0
            for container in containers:
                title_of_product = container.find("a", {"class" : "product-item-link"}).text.strip()
                price_of_product = container.find("span", {"class" : "price"}).text
                price_of_product = price_filter(price_of_product)
                image_of_product = container.find("div", {"class" : "product-item-photo"}).a.img['src']
                link_of_product = container.find("div", {"class" : "product-item-photo"}).a['href']
                if(price_of_product == '0'):
                    print("")
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : link_of_product, "site" : "https://www.olizstore.com/pub/media/porto/sticky_logo/default/olizLogo.png"})
                    i = i + 1
                    if i == 15:
                        break
    except:
        pass

    return product
    
if __name__ == '__main__':
    app.run(debug=True, port=8080)