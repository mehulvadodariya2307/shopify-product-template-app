<template>
  <Page>
    <div style="margin: 20px">
      <Grid>
        <GridCell
          :column-span="{ xs: 6, sm: 6, md: 6, lg: 10, xl: 10 }"
        ></GridCell>
        <GridCell :column-span="{ xs: 6, sm: 6, md: 6, lg: 2, xl: 2 }">
          <div style="float: right">
            <Button
              variant="primary"
              @click.stop.prevent="
                $router.push({
                  name: 'product',
                })
              "
            >
              Add product
            </Button>
          </div>
        </GridCell>
      </Grid>
    </div>
    <Grid>
      <GridCell :column-span="{ xs: 6, sm: 6, md: 6, lg: 9, xl: 9 }">
        <TextField
          v-model="searchValue"
          autoComplete="off"
          :clearButton="true"
          placeholder="Search Product"
          inputMode="text"
          @change="searchOnKeyUp"
          @clear-button-click="(searchValue = ''), searchOnKeyUp();"
        >
          <template #prefix>
            <Icon :source="SearchIcon" />
          </template>
        </TextField>
      </GridCell>
      <GridCell :column-span="{ xs: 6, sm: 6, md: 6, lg: 3, xl: 3 }">
        <div style="float: right">
          <Pagination
            v-if="allProducts.length"
            :has-previous="enablePreviousButton"
            :has-next="enableNextButton"
            :nextKeys="['k']"
            :previousKeys="['j']"
            nextTooltip="Next"
            previousTooltip="Previous"
            @previous="handlePrevious"
            @next="handleNext"
          >
            Results
          </Pagination>
        </div>
      </GridCell>
    </Grid>
    <div style="padding-top: 15px">
      <IndexTable
        :headings="headings"
        :item-count="allProducts.length"
        :loading="loadData"
        :selectable="false"
      >
        <IndexTableRow
          v-for="(
            { id, title, body_html, price, product_handle }, index
          ) in allProducts"
          :key="id"
          :id="id"
          :position="index"
        >
          <IndexTableCell>
            {{ title }}
          </IndexTableCell>
          <IndexTableCell>
            {{ body_html }}
          </IndexTableCell>
          <IndexTableCell>
            {{ price }}
          </IndexTableCell>
          <IndexTableCell :style="{ width: '0px' }">
            <!-- preview btn -->
            <Button @click.stop.prevent="gotoPreviewPage(product_handle)">
              <Icon :source="ExternalIcon" />
            </Button>
          </IndexTableCell>
        </IndexTableRow>
      </IndexTable>
    </div>
    <hr style="border-top: 0px solid lightgray" />
    <Grid
      :columns="{ xs: 1, sm: 1, md: 1, lg: 1, xl: 1 }"
      style="display: flex; justify-content: center"
    >
      <span v-if="allProducts.length">
        {{ `Showing ${allProducts.length} of ${total} results` }}
      </span>
    </Grid>
    <Grid
      :columns="{ xs: 1, sm: 1, md: 1, lg: 1, xl: 1 }"
      style="display: flex; justify-content: end; padding-top: 15px"
    >
      <Pagination
        v-if="allProducts.length"
        :has-previous="enablePreviousButton"
        :has-next="enableNextButton"
        :nextKeys="['k']"
        :previousKeys="['j']"
        nextTooltip="Next"
        previousTooltip="Previous"
        @previous="handlePrevious"
        @next="handleNext"
      >
        Results
      </Pagination>
    </Grid>
  </Page>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { DeleteIcon, InfoIcon, SearchIcon, ExternalIcon } from "../icons";

const router = useRouter();
const allProducts = ref([]);
const searchValue = ref("");
const perPage = ref(5);
const defaultPage = ref(0);
const enablePreviousButton = ref(false);
const enableNextButton = ref(false);
const total = ref(0);
const loadData = ref(false);
const headings = [
  { title: "Title" },
  { title: "Decription" },
  { title: "Price" },
  { title: "Action" },
];
// get data
const getTemplate = async () => {
  loadData.value = true;
  await utils.getSessionToken(app);
  await axios
    .post("api/products", {
      limit: perPage.value,
      offset: perPage.value * defaultPage.value,
      search: searchValue.value,
    })
    .then((res) => {
      if (res.data.success) {
        allProducts.value = res.data.data;
        total.value = res.data.total;
        var totalPage = Math.ceil(total.value / perPage.value);
        enablePreviousButton.value = false;
        if (defaultPage.value > 0) {
          enablePreviousButton.value = true;
        }
        enableNextButton.value = false;
        if (
          (defaultPage.value == 0 && totalPage > 1) ||
          totalPage - 1 > defaultPage.value
        ) {
          enableNextButton.value = true;
        }
        loadData.value = false;
      }
    })
    .catch((error) => {
      console.log(error);
    });
};
const handleNext = async () => {
  defaultPage.value = defaultPage.value + 1;
  await getTemplate();
};
const handlePrevious = async () => {
  defaultPage.value = defaultPage.value - 1;
  await getTemplate();
};
const searchOnKeyUp = async () => {
  defaultPage.value = 0;
  await getTemplate();
};
const gotoPreviewPage = (handle) => {
  window.open("https://" + window.auth.name + "/products/" + handle);
};
onMounted(async () => {
  await getTemplate();
});
</script>
<style>
.Polaris-IndexTable__EmptySearchResultWrapper {
  display: none;
}
</style>
