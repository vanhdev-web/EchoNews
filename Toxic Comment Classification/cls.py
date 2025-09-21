import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import classification_report
from sklearn.feature_selection import SelectKBest, chi2
from sklearn.pipeline import Pipeline
from sklearn.tree import DecisionTreeClassifier
from sklearn.ensemble import RandomForestClassifier
import pickle


def transform_data(data, train = True):
    toxic_labels = ["toxic", "severe_toxic", "obscene", "threat", "insult", "identity_hate"]
    data["is_toxic"] = data[toxic_labels].any(axis=1).astype(int)
    if train:
        return data[["comment_text","is_toxic"]]
    else:
        return data["is_toxic"]

test_data = pd.read_csv("test.csv")
train_data = pd.read_csv("train.csv")
test_labels = pd.read_csv("test_labels.csv")


train_data = transform_data(train_data)
# print(train_data.shape) #(159571, 2)
# print(test_data.shape) #(153164, 2)
target = "is_toxic"
x_train = train_data["comment_text"]
y_train = train_data[target]
x_test = test_data["comment_text"]
y_test = transform_data(test_labels, train= False)

mask = y_test != -1
x_test = x_test[mask]
y_test = y_test[mask]

clf = Pipeline(steps=[
    ("tfidf", TfidfVectorizer(stop_words="english", ngram_range=(1,3))),
    ("selector", SelectKBest(chi2, k=5000)),
    ("model", LogisticRegression(random_state=42, max_iter=1000)),
    # ("model", DecisionTreeClassifier(criterion= "gini")),
    # ("model", RandomForestClassifier(random_state=0, verbose= 2)),

])

clf.fit(x_train, y_train)
y_pred = clf.predict(x_test)
prediction = classification_report(y_test, y_pred)
print(prediction)

#logistic regression:
#               precision    recall  f1-score   support
#
#            0       0.44      0.95      0.60     57735
#            1       0.90      0.25      0.39     95429
#
#     accuracy                           0.52    153164
#    macro avg       0.67      0.60      0.50    153164
# weighted avg       0.73      0.52      0.47    153164


#decision tree
#               precision    recall  f1-score   support
#
#            0       0.45      0.87      0.59     57735
#            1       0.82      0.35      0.49     95429
#
#     accuracy                           0.55    153164
#    macro avg       0.63      0.61      0.54    153164
# weighted avg       0.68      0.55      0.53    153164

#              precision    recall  f1-score   support

#            0       0.45      0.91      0.60     57735
#            1       0.85      0.32      0.47     95429
#
#     accuracy                           0.54    153164
#    macro avg       0.65      0.61      0.53    153164
# weighted avg       0.70      0.54      0.52    153164

with open("toxic_pipeline.pkl", "wb") as f:
    pickle.dump(clf, f)




