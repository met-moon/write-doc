<template>
  <el-button @click="visible = true">Button</el-button>
  <el-dialog :visible.sync="visible" title="Hello world">
    <p>Try Element</p>
  </el-dialog>
</template>