# -*- coding:utf-8 -*-

import sys
import gensim
import pprint

args = sys.argv

def hasEnoughArgs(args):
    return len(args) == 4

if not hasEnoughArgs(args):
    print('Too few arguments.')
    print(args)
    quit(1)

print('has')
print("第1引数：" + args[1])
print("第2引数：" + args[2])
print("第3引数：" + args[3])

word2vec_model = gensim.models.KeyedVectors.load_word2vec_format('.data/traind.bin', binary=True)

pprint.pprint(word2vec_model.vocab)
#pprint.pprint(word2vec_model.most_similar(positive=['one', 'two']))
