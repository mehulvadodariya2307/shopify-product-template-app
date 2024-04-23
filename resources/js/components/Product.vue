<template>
  <Page
    :backAction="{ content: 'Home', onAction: gotoHome }"
    title="Add product"
  >
  <Loader v-if="isLoading"/>
    <div>
      <Layout>
        <LayoutSection>
          <Card>
            <div style="margin: 10px">
              <TextField
                v-model="title"
                label="Title"
                type="text"
                :error="titleError"
              />

              <TextField
                v-model="body_html"
                type="text"
                label="Description"
                :multiline="5"
                :error="bodyError"
              />

              <TextField
                v-model="price"
                type="number"
                label="Price"
                :error="priceError"
              />

              <Button
                @click="handleSubmit"
                type="button"
                variant="primary"
                style="margin-top: 10px"
              >
                Add Product
              </Button>
            </div>
          </Card>
        </LayoutSection>
      </Layout>
    </div>
  </Page>
</template>
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Loader from "./Loader"
import { toster } from "../toster";

const router = useRouter();

const isLoading = ref(false);
const title = ref("");
const titleError = ref("");
const body_html = ref("");
const bodyError = ref("");
const price = ref(0);
const priceError = ref("");

const gotoHome = () => {
  router.push({ name: "home" });
};

const handleSubmit = () => {
  titleError.value = "";
  bodyError.value = "";
  priceError.value = "";

  if (!title.value) {
    titleError.value = "Title is required";
    return;
  }
  if (!body_html.value) {
    bodyError.value = "Description is required";
    return;
  }
  if (!price.value) {
    priceError.value = "price is required";
    return;
  }
  isLoading.value = true;
  axios
    .post("api/product/add", {
      title: title.value,
      body_html: body_html.value,
      price: parseFloat(price.value).toFixed(2),
    })
    .then((res) => {
      if (res.data.success == false) {
        if (res.data.errors) {
          for (const property in res.data.errors) {
            if (property == "title") {
              titleError.value = res.data.errors[property][0];
            } else if (property == "body_html") {
              bodyError.value = res.data.errors[property][0];
            } else if (property == "price") {
              priceError.value = res.data.errors[property][0];
            }
          }
          toster("validation failed", true);
        }
        toster(res.data.message, true);
        isLoading.value = false;
      } else {
        toster(res.data.message);
        isLoading.value = false;
        gotoHome();
      }
    });
};
</script>
<style>
.Polaris-Label__Text {
  font-size: 15px;
}
</style>
