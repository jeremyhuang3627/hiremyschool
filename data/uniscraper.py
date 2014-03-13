# version 2.0 
# incorporated the new directory system. scraping seessions are organised into folders

from selenium import webdriver

from selenium.webdriver.common.keys import Keys

from selenium.webdriver.support.ui import Select, WebDriverWait

from selenium.webdriver.support import expected_conditions as EC

from openpyxl.workbook import Workbook

from openpyxl.writer.excel import ExcelWriter

from openpyxl.cell import get_column_letter

from openpyxl import load_workbook

from time import strftime

from datetime import datetime

import time

import re

import sys

import easygui

import os


driver = webdriver.Firefox()


for i in range(45):
        driver.get("http://collegestats.org/colleges/all/name/"+str(i+1))
        driver.implicitly_wait(10)
        tr_count = len(driver.find_elements_by_xpath("//*[@id='content']/table/tbody/tr"))-1
        for i in range(tr_count): 
                uni = driver.find_element_by_xpath("//*[@id='content']/table/tbody/tr["+str(i+2)+"]/td[1]/a").text
                fe = open("uni.txt","a")
                fe.write(uni+"\n")
                fe.close()
                print uni+" complete"
        fo = open("pos.txt","w")
        fo.write(str(i))
        fo.close()

print "Scraping complete"
