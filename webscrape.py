# from bs4 import BeautifulSoup
# from selenium import webdriver
# from urllib.request import urlopen
# import time
# from flask import Flask, jsonify, request
# app = Flask(__name__)

# @app.route('/<string:search_query>', methods=['GET'])
# def test(search_query):
#     chrome_path = r"C:\Users\Bikash\Desktop\chromedriver\chromedriver.exe"
#     driver = webdriver.Chrome(chrome_path)

#     query_for_comparison = search_query.replace("+", " ")

#     driver.get("https://muncha.com/Shop/Search?merchantID=1&CategoryID=0&q=" +search_query)
#     time.sleep(3)

#     product=[]

#     html = driver.page_source

#     parsed_html = BeautifulSoup(html, "html.parser")

#     containers = parsed_html.find_all("div", {"class" : "product"})

#     for container in containers:
#         title_of_product = container.find("h5", {"class" : "product-caption-title-sm"}).text
#         price_of_product = container.find("span", {"class" : "product-caption-price-new"}).text
#         if (title_of_product.lower().find(query_for_comparison.lower())) != -1:
#             product.append({"Title" : title_of_product, "Price": price_of_product, "Site" : "Muncha"})

#     driver.get("https://www.daraz.com.np/catalog/?q=" + search_query +"&_keyori=ss&from=input&spm=a2a0e.11779170.search.go.287d2d2bR6H8P6")
#     time.sleep(3)

#     html = driver.page_source

#     parsed_html = BeautifulSoup(html, "html.parser")

#     containers = parsed_html.find_all("div", {"class" : "c2prKC"})

#     for container in containers:
#         title_of_product = container.find("div", {"class" : "c16H9d"}).a['title']
#         price_of_product = container.find("span", {"class" : "c13VH6"}).text

#         if (title_of_product.lower().find(query_for_comparison.lower())) != -1:
#             product.append({"Title" : title_of_product, "Price": price_of_product, "Site" : "Daraz"})

#     driver.get("https://www.sastodeal.com/search.html?q="+ search_query +"&hpp=16&idx=sastodeal_products&p=0&is_v=1&isProduct=N")
#     time.sleep(3)

#     html = driver.page_source

#     parsed_html = BeautifulSoup(html, "html.parser")

#     containers = parsed_html.find_all("article", {"class" : "hit"})

#     for container in containers:
#         title_of_product = container.find("div", {"class" : "product-name"}).a.text
#         price_of_product = container.find("div", {"class" : "product-price"}).text
#         if (title_of_product.lower().find(search_query.lower())) != -1:
#             product.append({"Title" : title_of_product, "Price": price_of_product, "Site" : "Sastodeal"})

#     return jsonify(product)

# if __name__ == '__main__':
#     app.run(debug=True, port=8080)

from bs4 import BeautifulSoup
from selenium import webdriver
from urllib.request import urlopen
import time
from flask import Flask, jsonify, request
app = Flask(__name__)

@app.route('/<string:search_query>', methods=['GET'])
def test(search_query):
    query = [
        ['https://muncha.com/Shop/Search?merchantID=1&CategoryID=0&q=' + search_query, 'https://muncha.com', 'muncha', ['div', 'product'], ['img', 'product-img-primary', 'alt'], ['span', 'product-caption-price-new'], ['img', 'product-img-primary', 'src'], ['a', 'product-link', 'href']],
        ['https://www.daraz.com.np/catalog/?q=' + search_query +'&_keyori=ss&from=input&spm=a2a0e.11779170.search.go.287d2d2bR6H8P6', '', 'daraz', ['div', 'c2prKC'], ['div', 'c16H9d'], ['span', 'c13VH6'], ['img', 'c1ZEkM ', 'alt'], ['div', 'cRjKsc']],
        ['https://www.sastodeal.com/search.html?q='+ search_query +'&hpp=16&idx=sastodeal_products&p=0&is_v=1&isProduct=N', '', 'sastodeal', ['article', 'hit'], ['div', 'product-name'], ['div', 'product-price'], ['div', 'product-picture'], ['div', 'product-picture']]
    ]
    results = webscrape(query, search_query)
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

def webscrape(query, search_query):
    chrome_path = r"C:\Users\Bikash\Desktop\chromedriver\chromedriver.exe"
    driver = webdriver.Chrome(chrome_path)

    product = []

    for q in query:
        driver.get(q[0])
        time.sleep(5)

        html = driver.page_source

        parsed_html = BeautifulSoup(html, "html.parser")

        containers = parsed_html.find_all(q[3][0], {"class" : q[3][1]})

        if len(containers) <= 5:
            for container in containers:
                global title_of_product
                price_of_product = container.find(q[5][0], {"class" : q[5][1]}).text
                
                price_of_product = price_filter(price_of_product)
                if(q[2] == "muncha"):
                    link_of_product = container.find(q[7][0], {"class" : q[7][1]})[q[7][2]]
                    title_of_product = container.find(q[4][0], {"class" : q[4][1]})[q[4][2]]
                    image_of_product = container.find(q[6][0], {"class" : q[6][1]})[q[6][2]]
                elif(q[2] == "daraz"):
                    link_of_product = container.find(q[7][0], {"class" : q[7][1]}).a['href']
                    image_of_product = None
                elif(q[2] == "sastodeal"):
                    image_of_product = container.find(q[6][0], {"class" : q[6][1]}).a.img['src']
                    link_of_product = container.find(q[7][0], {"class" : q[7][1]}).a['href']
                elif(q[2] == 'sastodeal'):
                    title_of_product = container.find(q[4][0], {"class" : q[4][1]}).a.text

                if(q[2] == 'daraz'):
                    product.append({"title" : "Daraz product", "price": price_of_product, "image": image_of_product, "link" : q[1] + link_of_product, "site" : q[2]})
                elif(q[2] == 'sastodeal'):
                    product.append({"title" : "Sasto deal product", "price": price_of_product, "image": image_of_product, "link" : q[1] + link_of_product, "site" : q[2]})
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : q[1] + link_of_product, "site" : q[2]})
        else:
            i = 0
            for container in containers:
                price_of_product = container.find(q[5][0], {"class" : q[5][1]}).text
                price_of_product = price_filter(price_of_product)

                if(q[2] == "muncha"):
                    link_of_product = container.find(q[7][0], {"class" : q[7][1]})[q[7][2]]
                    title_of_product = container.find(q[4][0], {"class" : q[4][1]})[q[4][2]]
                    image_of_product = container.find(q[6][0], {"class" : q[6][1]})[q[6][2]]
                elif(q[2] == "daraz"):
                    link_of_product = container.find(q[7][0], {"class" : q[7][1]}).a['href']
                    image_of_product = None
                elif(q[2] == "sastodeal"):
                    image_of_product = container.find(q[6][0], {"class" : q[6][1]}).a.img['src']
                    link_of_product = container.find(q[7][0], {"class" : q[7][1]}).a['href']
                elif(q[2] == 'sastodeal'):
                    title_of_product = container.find(q[4][0], {"class" : q[4][1]}).a.text

                if(q[2] == 'daraz'):
                    product.append({"title" : "Daraz product", "price": price_of_product, "image": image_of_product, "link" : q[1] + link_of_product, "site" : q[2]})
                elif(q[2] == 'sastodeal'):
                    product.append({"title" : "Sasto deal product", "price": price_of_product, "image": image_of_product, "link" : q[1] + link_of_product, "site" : q[2]})
                else:
                    product.append({"title" : title_of_product, "price": price_of_product, "image": image_of_product, "link" : q[1] + link_of_product, "site" : q[2]})
                i = i + 1
                if i == 5:
                    break
    return product
    

if __name__ == '__main__':
    app.run(debug=True, port=8080)